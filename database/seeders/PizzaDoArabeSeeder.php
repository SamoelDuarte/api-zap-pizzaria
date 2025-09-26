<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PizzaDoArabeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar ID da categoria "Pizzas Clássicas"
        $categoryPizzaClassicasId = DB::table('categories')->where('name', 'Pizzas Clássica')->value('id');
        
        // Se não encontrar, criar a categoria
        if (!$categoryPizzaClassicasId) {
            $categoryPizzaClassicasId = DB::table('categories')->insertGetId([
                'name' => 'Pizzas Clássica',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Buscar ID da categoria "Pizzas Doces"
        $categoryPizzaDocesId = DB::table('categories')->where('name', 'Pizzas Doces')->value('id');
        
        // Se não encontrar, criar a categoria
        if (!$categoryPizzaDocesId) {
            $categoryPizzaDocesId = DB::table('categories')->insertGetId([
                'name' => 'Pizzas Doces',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pizzas Salgadas do Árabe
        $pizzasDoArabe = [
            ['name' => 'A MODA DO MOTOQUEIRO', 'description' => 'Frango, cheddar e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'A MODA DO MOTOQUEIRO II', 'description' => 'Calabresa, catupiry, cebola e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'A MODA DO PIZZAIOLO', 'description' => 'Calabresa, frango, milho e bacon', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'A MODA DO ÁRABE', 'description' => 'Frango, mussarela, tomate, catupiry e bacon', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'A MODA DA CASA', 'description' => 'Calabresa, catupiry, bacon, cebola e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'A PAULISTA', 'description' => 'Mussarela, calabresa e parmesão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'ATUM II', 'description' => 'Atum, cebola e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'BACON', 'description' => 'Bacon e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'BAIANA ESPECIAL', 'description' => 'Calabresa moída, cebola, ovo, pimenta e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'BRÓCOLIS', 'description' => 'Brócolis refogado, alho e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'CALAMUSSA', 'description' => 'Calabresa fatiada, mussarela e cebola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'CATUPIRY C/ CALABRESA', 'description' => 'Calabresa e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'CAIPIRA', 'description' => 'Frango, milho, tomate, mussarela ou catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'CROCANTE', 'description' => 'Frango, mussarela e batata palha', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'CALABRESA', 'description' => 'Calabresa e cebola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'CARNE SECA I', 'description' => 'Carne seca, cebola e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'CARNE SECA II', 'description' => 'Carne seca, bacon, cebola e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'DOIS QUEIJOS', 'description' => 'Mussarela e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'DORITOS', 'description' => 'Mussarela, frango e doritos', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'ESPANHOLA', 'description' => 'Mussarela, calabresa, bacon, ovos e cebola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'FRANGO C/ CATUPIRY', 'description' => 'Frango desfiado com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'ITALIANA', 'description' => 'Atum, cebola, bacon e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'JARDINEIRA', 'description' => 'Presunto, frango, milho e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'MUSSARELA', 'description' => 'Mussarela e tomate', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'MARGUERITA', 'description' => 'Mussarela, parmesão, tomate e manjericão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'PALMITO', 'description' => 'Palmito, mussarela e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'PORTUGUESA', 'description' => 'Presunto, ovo, cebola e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'PORTUGUESA II', 'description' => 'Presunto, cebola, ovo e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'PORTUGUESA ESPECIAL', 'description' => 'Presunto, mussarela, cebola, ovo e bacon', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'PEITO DE PERU', 'description' => 'Mussarela, tomate e peito de peru', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'TRADIÇÃO', 'description' => 'Presunto, calabresa, bacon e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'TRÊS QUEIJOS', 'description' => 'Mussarela, catupiry e parmesão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaClassicasId],
        ];

        // Pizzas Doces do Árabe
        $pizzasDocesDoArabe = [
            ['name' => 'PRESTÍGIO', 'description' => 'Chocolate e coco ralado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => "M&M'S", 'description' => "Chocolate e M&M's", 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 39.99, 'category_id' => $categoryPizzaDocesId],
        ];

        // Inserir pizzas salgadas
        foreach ($pizzasDoArabe as $pizza) {
            // Verificar se a pizza já existe para evitar duplicatas
            $exists = DB::table('products')
                ->where('name', $pizza['name'])
                ->where('category_id', $pizza['category_id'])
                ->exists();
                
            if (!$exists) {
                DB::table('products')->insert(array_merge($pizza, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // Inserir pizzas doces
        foreach ($pizzasDocesDoArabe as $pizza) {
            // Verificar se a pizza já existe para evitar duplicatas
            $exists = DB::table('products')
                ->where('name', $pizza['name'])
                ->where('category_id', $pizza['category_id'])
                ->exists();
                
            if (!$exists) {
                DB::table('products')->insert(array_merge($pizza, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        $this->command->info('Pizzas do Árabe inseridas com sucesso!');
        $this->command->info('Total de pizzas salgadas: ' . count($pizzasDoArabe));
        $this->command->info('Total de pizzas doces: ' . count($pizzasDocesDoArabe));
    }
}