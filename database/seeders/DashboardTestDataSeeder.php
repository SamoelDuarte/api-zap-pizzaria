<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DashboardTestDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');
        
        // Criar status de pedidos se não existirem
        $statuses = [
            ['id' => '1', 'name' => 'Pendente', 'color' => '#ffc107'],
            ['id' => '2', 'name' => 'Em Preparo', 'color' => '#28a745'],
            ['id' => '3', 'name' => 'Saiu para Entrega', 'color' => '#007bff'],
            ['id' => '4', 'name' => 'Entregue', 'color' => '#17a2b8'],
            ['id' => '5', 'name' => 'Cancelado', 'color' => '#dc3545'],
        ];

        foreach ($statuses as $status) {
            OrderStatus::updateOrCreate(
                ['id' => $status['id']],
                ['name' => $status['name'], 'color' => $status['color']]
            );
        }

        // Obter produtos existentes
        $products = Product::all();
        $pizzaProducts = $products->filter(function($product) {
            return str_contains(strtolower($product->name), 'pizza') || 
                   !str_contains(strtolower($product->name), 'coca') && 
                   !str_contains(strtolower($product->name), 'fanta') &&
                   !str_contains(strtolower($product->name), 'guaraná') &&
                   !str_contains(strtolower($product->name), 'dolly');
        });
        
        $bebidaProducts = $products->diff($pizzaProducts);

        // Criar 50 clientes de teste
        $customers = [];
        for ($i = 0; $i < 50; $i++) {
            $customer = Customer::create([
                'name' => $faker->name,
                'jid' => '55' . $faker->unique()->numerify('11#########'),
                'public_place' => $faker->streetName,
                'number' => $faker->buildingNumber,
                'neighborhood' => $faker->citySuffix,
                'city' => 'São Paulo',
                'state' => 'SP',
                'zipcode' => $faker->postcode,
                'complement' => $faker->optional()->secondaryAddress,
                'created_at' => $faker->dateTimeBetween('-6 months', 'now')
            ]);
            $customers[] = $customer;
        }

        // Criar pedidos dos últimos 6 meses
        for ($i = 0; $i < 200; $i++) {
            $customer = $faker->randomElement($customers);
            
            // Data do pedido (mais concentrada nos últimos 3 meses)
            $orderDate = $faker->biasedNumberBetween(1, 100) > 30 
                ? $faker->dateTimeBetween('-3 months', 'now')
                : $faker->dateTimeBetween('-6 months', '-3 months');

            $order = Order::create([
                'customer_id' => $customer->id,
                'status_id' => $faker->randomElement([
                    '4', '4', '4', '4', '4', '4', '4', '4',  // 8x Entregue (80%)
                    '2', '2',  // 2x Em Preparo (20%)
                    '1', '3', '5'  // 1x cada outros status
                ]),
                'delivery_fee' => $faker->randomFloat(2, 3, 15),
                'observation' => $faker->optional(0.3)->sentence,
                'change_for' => $faker->optional(0.2)->randomFloat(2, 50, 200),
                'created_at' => $orderDate,
                'updated_at' => $orderDate
            ]);

            // Criar itens do pedido (1 a 4 itens por pedido)
            $numItems = $faker->numberBetween(1, 4);
            
            for ($j = 0; $j < $numItems; $j++) {
                // 70% chance de ser pizza, 30% de ser bebida
                $isPizza = $faker->boolean(70);
                $selectedProducts = $isPizza ? $pizzaProducts : $bebidaProducts;
                
                if ($selectedProducts->count() > 0) {
                    $product = $selectedProducts->random();
                    $quantity = $faker->numberBetween(1, 3);
                    $price = $product->price;
                    
                    // Adicionar variação de preço para simular promoções/bordas
                    if ($isPizza) {
                        $price += $faker->optional(0.3)->randomFloat(2, 3, 8); // Borda
                    }
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $price,
                        'quantity' => $quantity,
                        'total' => $price * $quantity,
                        'crust' => $isPizza ? $faker->optional(0.3)->randomElement(['Catupiry', 'Cheddar', 'Chocolate']) : null,
                        'crust_price' => $isPizza && $faker->boolean(30) ? $faker->randomFloat(2, 3, 8) : 0,
                        'observation' => $faker->optional(0.2)->sentence(3),
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate
                    ]);
                }
            }
        }

        $this->command->info('Dados de teste para o dashboard criados com sucesso!');
    }
}
