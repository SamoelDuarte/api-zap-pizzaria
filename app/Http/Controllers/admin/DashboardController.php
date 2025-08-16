<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Data filters
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        // Ensure dates are Carbon instances
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        // Basic stats
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Total revenue - soma order_items.total + delivery_fee
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with('items')
            ->get()
            ->sum(function ($order) {
                return $order->items->sum('total') + $order->delivery_fee;
            });
        
        $totalCustomers = Customer::whereBetween('created_at', [$startDate, $endDate])->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Top selling products
        $topProducts = $this->getTopSellingProducts($startDate, $endDate);
        
        // Most valuable customers
        $topCustomers = $this->getTopCustomers($startDate, $endDate);
        
        // Sales by day chart data
        $salesByDay = $this->getSalesByDay($startDate, $endDate);
        
        // Orders by status
        $ordersByStatus = $this->getOrdersByStatus($startDate, $endDate);
        
        // Revenue vs orders comparison
        $revenueComparison = $this->getRevenueComparison($startDate, $endDate);
        
        // Product categories performance
        $categoryPerformance = $this->getCategoryPerformance($startDate, $endDate);
        
        // Recent orders
        $recentOrders = Order::with(['customer', 'items', 'status'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalOrders',
            'totalRevenue', 
            'totalCustomers',
            'averageOrderValue',
            'topProducts',
            'topCustomers',
            'salesByDay',
            'ordersByStatus',
            'revenueComparison',
            'categoryPerformance',
            'recentOrders',
            'startDate',
            'endDate'
        ));
    }

    private function getTopSellingProducts($startDate, $endDate, $limit = 10)
    {
        return OrderItem::select('name', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total) as total_revenue'))
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('name')
            ->orderBy('total_quantity', 'desc')
            ->take($limit)
            ->get();
    }

    private function getTopCustomers($startDate, $endDate, $limit = 10)
    {
        $customers = Customer::with(['orders' => function ($query) use ($startDate, $endDate) {
            $query->with('items')->whereBetween('created_at', [$startDate, $endDate]);
        }])->get();

        $customersWithStats = $customers->filter(function ($customer) {
            return $customer->orders->count() > 0;
        })->map(function ($customer) {
            $totalSpent = $customer->orders->sum(function ($order) {
                return $order->items->sum('total') + $order->delivery_fee;
            });
            
            return (object) [
                'id' => $customer->id,
                'name' => $customer->name,
                'jid' => $customer->jid,
                'phone' => substr($customer->jid ?? '', 2),
                'orders_count' => $customer->orders->count(),
                'total_spent' => $totalSpent
            ];
        });

        return $customersWithStats->sortByDesc('total_spent')->take($limit)->values();
    }

    private function getSalesByDay($startDate, $endDate)
    {
        $orders = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as orders_count')
        )
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get();

        $labels = [];
        $ordersData = [];
        $revenueData = [];

        // Se não há dados, retorna arrays vazios
        if ($orders->isEmpty()) {
            return [
                'labels' => [],
                'orders' => [],
                'revenue' => []
            ];
        }

        foreach ($orders as $order) {
            $labels[] = Carbon::parse($order->date)->format('d/m');
            $ordersData[] = (int) $order->orders_count;
            
            // Calcular receita do dia
            $dayRevenue = Order::with('items')
                ->whereDate('created_at', $order->date)
                ->get()
                ->sum(function ($o) {
                    return $o->items->sum('total') + ($o->delivery_fee ?? 0);
                });
            
            $revenueData[] = (float) round($dayRevenue, 2);
        }

        return [
            'labels' => $labels,
            'orders' => $ordersData,
            'revenue' => $revenueData
        ];
    }

    private function getOrdersByStatus($startDate, $endDate)
    {
        $results = DB::table('orders')
            ->leftJoin('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->select('order_statuses.name', DB::raw('COUNT(orders.id) as count'))
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('order_statuses.id', 'order_statuses.name')
            ->orderBy('count', 'desc')
            ->get();

        // Se não há resultados, retorna array vazio
        if ($results->isEmpty()) {
            return collect([]);
        }

        // Garantir que os valores são numéricos
        return $results->map(function ($item) {
            return (object) [
                'name' => $item->name ?? 'Sem Status',
                'count' => (int) $item->count
            ];
        });
    }

    private function getRevenueComparison($startDate, $endDate)
    {
        $currentOrders = Order::with('items')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        
        $currentPeriod = $currentOrders->sum(function ($order) {
            return $order->items->sum('total') + $order->delivery_fee;
        });

        $daysDiff = $startDate->diffInDays($endDate);
        $previousStart = $startDate->copy()->subDays($daysDiff + 1);
        $previousEnd = $startDate->copy()->subDay();

        $previousOrders = Order::with('items')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->get();
            
        $previousPeriod = $previousOrders->sum(function ($order) {
            return $order->items->sum('total') + $order->delivery_fee;
        });

        $percentageChange = $previousPeriod > 0 
            ? (($currentPeriod - $previousPeriod) / $previousPeriod) * 100 
            : 0;

        return [
            'current' => $currentPeriod,
            'previous' => $previousPeriod,
            'percentage' => round($percentageChange, 2)
        ];
    }

    private function getCategoryPerformance($startDate, $endDate)
    {
        return DB::table('order_items')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', function($join) {
                $join->on('order_items.name', '=', 'products.name');
            })
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                DB::raw('COALESCE(categories.name, "Outros") as category_name'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }

    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'sales');
        $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()))->startOfDay();
        $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()))->endOfDay();

        switch ($type) {
            case 'sales':
                return response()->json($this->getSalesByDay($startDate, $endDate));
            
            case 'products':
                return response()->json($this->getTopSellingProducts($startDate, $endDate));
            
            case 'customers':
                return response()->json($this->getTopCustomers($startDate, $endDate));
            
            case 'status':
                return response()->json($this->getOrdersByStatus($startDate, $endDate));
            
            case 'categories':
                return response()->json($this->getCategoryPerformance($startDate, $endDate));
            
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }
}
