@extends('admin.layout.app')

@section('title', 'Dashboard - Analytics')

@section('head')
<link href="{{ asset('/assets/admin/css/dashboard.css') }}" rel="stylesheet">
<style>
    .dashboard-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        color: white;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    
    .dashboard-card.revenue {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .dashboard-card.customers {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .dashboard-card.average {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .dashboard-card.orders {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .card-value {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .card-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .chart-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .top-products-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
    }
    
    .badge-pizza {
        background: linear-gradient(45deg, #ff6b6b, #ee5a24);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
    }
    
    .badge-bebida {
        background: linear-gradient(45deg, #74b9ff, #0984e3);
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .status-pendente {
        background: #fef3cd;
        color: #856404;
    }
    
    .status-em-preparo {
        background: #d4edda;
        color: #155724;
    }
    
    .status-entregue {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 0;
        margin: -30px -30px 30px -30px;
        text-align: center;
    }
    
    .page-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .page-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
    }
    
    .revenue-comparison {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 15px;
    }
    
    .comparison-arrow {
        font-size: 1.5rem;
    }
    
    .comparison-positive {
        color: #38f9d7;
    }
    
    .comparison-negative {
        color: #f5576c;
    }
    
    .dashboard-icon {
        font-size: 3rem;
        opacity: 0.8;
        margin-bottom: 15px;
    }
    
    @media (max-width: 768px) {
        .card-value {
            font-size: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .dashboard-card {
            padding: 20px;
            margin-bottom: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="container">
        <h1 class="page-title">🍕 Dashboard Analytics</h1>
        <p class="page-subtitle">Análise completa de vendas e performance da pizzaria</p>
    </div>
</div>

<div class="container-fluid">
    <!-- Filtros de Data -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <h5 class="mb-3"><i class="fas fa-calendar-alt"></i> Filtros de Período</h5>
                <form id="dateFilterForm" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Data Inicial</label>
                        <input type="date" class="form-control" id="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Data Final</label>
                        <input type="date" class="form-control" id="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block w-100">
                            <i class="fas fa-search"></i> Aplicar Filtro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card orders">
                <div class="dashboard-icon">📊</div>
                <div class="card-value">{{ $totalOrders }}</div>
                <div class="card-label">Total de Pedidos</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card revenue">
                <div class="dashboard-icon">💰</div>
                <div class="card-value">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</div>
                <div class="card-label">Receita Total</div>
                @if(isset($revenueComparison))
                <div class="revenue-comparison">
                    <span class="comparison-arrow {{ $revenueComparison['percentage'] >= 0 ? 'comparison-positive' : 'comparison-negative' }}">
                        {{ $revenueComparison['percentage'] >= 0 ? '↗️' : '↘️' }}
                    </span>
                    <small>{{ abs($revenueComparison['percentage']) }}% vs período anterior</small>
                </div>
                @endif
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card customers">
                <div class="dashboard-icon">👥</div>
                <div class="card-value">{{ $totalCustomers }}</div>
                <div class="card-label">Novos Clientes</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="dashboard-card average">
                <div class="dashboard-icon">📈</div>
                <div class="card-value">R$ {{ number_format($averageOrderValue, 2, ',', '.') }}</div>
                <div class="card-label">Ticket Médio</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Gráfico de Vendas por Dia -->
        <div class="col-lg-8 mb-4">
            <div class="chart-container">
                <h5 class="chart-title">📊 Vendas por Dia</h5>
                <div style="position: relative; height: 400px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de Pedidos por Status -->
        <div class="col-lg-4 mb-4">
            <div class="chart-container">
                <h5 class="chart-title">🎯 Status dos Pedidos</h5>
                <div style="position: relative; height: 400px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Produtos Mais Vendidos -->
        <div class="col-lg-6 mb-4">
            <div class="top-products-table">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> 🍕 Pizzas Mais Vendidas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th>Vendidos</th>
                                    <th>Receita</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $index => $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-warning me-2">{{ $index + 1 }}º</span>
                                            <div>
                                                <strong>{{ $product->name }}</strong>
                                                @if(str_contains(strtolower($product->name), 'mussarela') || 
                                                     str_contains(strtolower($product->name), 'calabresa') || 
                                                     str_contains(strtolower($product->name), 'marguerita') ||
                                                     str_contains(strtolower($product->name), 'portuguesa') ||
                                                     str_contains(strtolower($product->name), 'frango') ||
                                                     str_contains(strtolower($product->name), 'catupiry') ||
                                                     !str_contains(strtolower($product->name), 'coca') && 
                                                     !str_contains(strtolower($product->name), 'fanta') && 
                                                     !str_contains(strtolower($product->name), 'guaraná') && 
                                                     !str_contains(strtolower($product->name), 'dolly'))
                                                    <span class="badge-pizza ms-2">Pizza</span>
                                                @else
                                                    <span class="badge-bebida ms-2">Bebida</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong>{{ $product->total_quantity }}x</strong></td>
                                    <td class="text-success"><strong>R$ {{ number_format($product->total_revenue, 2, ',', '.') }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Melhores Clientes -->
        <div class="col-lg-6 mb-4">
            <div class="top-products-table">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> 👑 Melhores Clientes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Pedidos</th>
                                    <th>Total Gasto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $index => $customer)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-success me-2">{{ $index + 1 }}º</span>
                                            <div>
                                                <strong>{{ $customer->name }}</strong><br>
                                                <small class="text-muted">{{ $customer->phone ?? substr($customer->jid ?? '', 2) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong>{{ $customer->orders_count }} pedidos</strong></td>
                                    <td class="text-success"><strong>R$ {{ number_format($customer->total_spent ?? 0, 2, ',', '.') }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Performance por Categoria -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="chart-container">
                <h5 class="chart-title">🏆 Performance por Categoria</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos Recentes -->
    <div class="row">
        <div class="col-12">
            <div class="top-products-table">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> 🕒 Pedidos Recentes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Itens</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>
                                        <strong>{{ $order->customer->name }}</strong><br>
                                        <small class="text-muted">{{ $order->customer->phone ?? substr($order->customer->jid ?? '', 2) }}</small>
                                    </td>
                                    <td>
                                        @foreach($order->items->take(2) as $item)
                                            <small>{{ $item->quantity }}x {{ $item->name }}</small><br>
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <small class="text-muted">+{{ $order->items->count() - 2 }} mais...</small>
                                        @endif
                                    </td>
                                    <td class="text-success"><strong>R$ {{ number_format($order->total_geral ?? 0, 2, ',', '.') }}</strong></td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $order->status->name ?? 'Pendente')) }}">
                                            {{ $order->status->name ?? 'Pendente' }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurações globais dos gráficos
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
    
    // Dados iniciais do gráfico de vendas
    const salesData = @json($salesByDay);
    console.log('Sales Data:', salesData);
    
    // Verificar se há dados
    if (!salesData.labels || salesData.labels.length === 0) {
        document.getElementById('salesChart').parentElement.innerHTML = 
            '<div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> Não há dados de vendas para o período selecionado</div>';
    } else {
        // Gráfico de Vendas por Dia
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesData.labels,
                datasets: [{
                    label: 'Receita (R$)',
                    data: salesData.revenue || [],
                    borderColor: 'rgb(102, 126, 234)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                }, {
                    label: 'Nº de Pedidos',
                    data: salesData.orders || [],
                    borderColor: 'rgb(245, 87, 108)',
                    backgroundColor: 'rgba(245, 87, 108, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Receita (R$)'
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        beginAtZero: true
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Nº de Pedidos'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Gráfico de Status dos Pedidos
    const statusData = @json($ordersByStatus);
    console.log('Status Data:', statusData);
    
    if (!statusData || statusData.length === 0) {
        document.getElementById('statusChart').parentElement.innerHTML = 
            '<div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> Não há dados de status para o período selecionado</div>';
    } else {
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusData.map(item => item.name || 'Sem Status'),
                datasets: [{
                    data: statusData.map(item => parseInt(item.count) || 0),
                    backgroundColor: [
                        'rgba(255, 107, 107, 0.8)',
                        'rgba(116, 185, 255, 0.8)',
                        'rgba(67, 233, 123, 0.8)',
                        'rgba(255, 195, 0, 0.8)',
                        'rgba(156, 39, 176, 0.8)',
                        'rgba(255, 159, 64, 0.8)'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Performance por Categoria
    const categoryData = @json($categoryPerformance);
    console.log('Category Data:', categoryData);
    
    if (!categoryData || categoryData.length === 0) {
        document.getElementById('categoryChart').parentElement.innerHTML = 
            '<div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> Não há dados de categoria para o período selecionado</div>';
    } else {
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: categoryData.map(item => item.category_name || 'Sem categoria'),
                datasets: [{
                    label: 'Receita (R$)',
                    data: categoryData.map(item => parseFloat(item.total_revenue) || 0),
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(245, 87, 108, 0.8)',
                        'rgba(67, 233, 123, 0.8)',
                        'rgba(255, 195, 0, 0.8)',
                        'rgba(156, 39, 176, 0.8)'
                    ],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                }
            }
        });
    }

    // Filtro de datas
    document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        if (startDate && endDate) {
            const url = new URL(window.location);
            url.searchParams.set('start_date', startDate);
            url.searchParams.set('end_date', endDate);
            window.location.href = url.toString();
        }
    });
});
</script>
@endsection

