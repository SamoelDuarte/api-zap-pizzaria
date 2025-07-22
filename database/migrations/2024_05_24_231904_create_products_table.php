<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('image');
            $table->decimal('price', 10, 2);
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Adicionar produtos para a categoria "Pizzas Clássicas"
        $categoryPizzaClassicasId = DB::table('categories')->where('name', 'Pizzas Clássica')->value('id');

        $pizzasClassicas = [
  ['name' => 'Alho', 'description' => 'mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 47.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Americana', 'description' => 'ovos e bacon coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Atum', 'description' => 'atum com cebola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Atum com mussarela', 'description' => 'atum e cebola coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 55.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Bacon', 'description' => 'mussarela com bacon', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 48.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Baiana', 'description' => 'calabresa, ovos, cebola e pimenta calabresa', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 48.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Baiana com mussarela', 'description' => 'calabresa, ovos e cebola cobertos com mussarela e pimenta calabresa', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 51.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Bauru', 'description' => 'presunto e tomates cobertos com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Bonança', 'description' => 'frango, milho, calabresa, lombo, catupiry e cheddar cobertos com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 55.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Brócolis com catupiry', 'description' => 'brócolis, bacon, catupiry e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 51.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Brócolis com mussarela', 'description' => 'brócolis e bacon cobertos com mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 51.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Caipira', 'description' => 'frango, milho e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 54.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Caipira com mussarela', 'description' => 'frango e milho cobertos com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 54.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Calabresa especial', 'description' => 'calabresa e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 50.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Calabresa', 'description' => 'calabresa e cebola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 45.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Calabria', 'description' => 'calabresa, cebola e bacon cobertos com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Calamussa', 'description' => 'calabresa assada coberta com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 48.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Camarão', 'description' => 'camarão cozido com mussarela ou catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 62.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Canadense', 'description' => 'lombo canadense com catupiry ou mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 54.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Carne seca', 'description' => 'carne seca desfiada e cebola coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Carne seca especial', 'description' => 'carne seca desfiada, cebola e catupiry coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 55.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Catumilho', 'description' => 'milho coberto com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 47.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Champignon', 'description' => 'champignon com mussarela ou catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Cinco queijos', 'description' => 'mussarela, provolone, parmesão, catupiry e gorgonzola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 55.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Costela', 'description' => 'costela desfiada, cebola, catupiry, coberto com mussarela e pimentão verde, vermelho e amarelo', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 57.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Cremosa', 'description' => 'presunto e catupiry coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Crocante', 'description' => 'frango desfiado e catupiry coberto com batata palha', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 54.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Dois queijos', 'description' => 'mussarela e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 47.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Escarola 1', 'description' => 'escarola coberta com mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 48.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Escarola 2', 'description' => 'escarola, bacon e catupiry coberto com mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 51.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Ferrary', 'description' => 'calabresa, cheddar e parmesão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 53.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Frango catupiry', 'description' => 'frango desfiado com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 51.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Frango com cheddar', 'description' => 'frango desfiado com cheddar', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 54.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Frango com mussarela', 'description' => 'frango desfiado coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 51.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Frango original', 'description' => 'frango desfiado com catupiry original', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 56.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Íntegra', 'description' => 'frango desfiado, palmito, catupiry, mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 53.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Jardineira', 'description' => 'frango, presunto, milho e ervilha coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Marguerita', 'description' => 'mussarela, tomate, parmesão e manjericão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 48.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Milho', 'description' => 'milho coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 47.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Moda da casa', 'description' => 'carne seca e brócolis com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 55.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Moda do pizziola', 'description' => 'peito de peru ralado e catupiry coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 58.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Mussarela Tradicional', 'description' => 'mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 45.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Palmito', 'description' => 'palmito coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Peito de peru com mussarela', 'description' => 'peito de peru ralado e tomate coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 56.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Peperone', 'description' => 'mussarela e rodelas de peperoni', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 55.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Portuguesa de peru', 'description' => 'peito de peru ralado, milho, cebola e ovo coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 56.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Portuguesa', 'description' => 'presunto, ervilha, ovos e cebola coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 51.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Quatro queijos', 'description' => 'mussarela, provolone, parmesão e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 51.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Romanesca', 'description' => 'presunto, bacon, champignon, catupiry e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 53.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Rúcula', 'description' => 'mussarela, rúcula e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Tomate seco', 'description' => 'mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 53.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Toscana', 'description' => 'calabresa moída, mussarela, tomate e parmesão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 51.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Três queijos', 'description' => 'mussarela, parmesão e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Vegetariana 1', 'description' => 'escarola, ervilha, palmito, mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaClassicasId],
  ['name' => 'Vegetariana 2', 'description' => 'brócolis, milho, palmito, mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaClassicasId]

        ];

        foreach ($pizzasClassicas as $pizza) {
            DB::table('products')->insert($pizza);
        }


        // Adicionar produtos para a categoria "Pizzas Broto"
        $categoryPizzaDocesId = DB::table('categories')->where('name', 'Pizzas Doces')->value('id');

        $pizzasDoces = [
            ['name' => 'Banamussa', 'description' => 'banana coberta com mussarela, leite condensado e canela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Banela', 'description' => 'banana, leite condensado e canela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 50.00, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Brigadeiro', 'description' => 'chocolate coberto com granulado e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Chocolate', 'description' => 'chocolate', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 48.00, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Choconana', 'description' => 'banana com chocolate', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Chocouva', 'description' => 'uva verde com chocolate e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Confette', 'description' => 'chocolate coberto com confette e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Prestigio', 'description' => 'chocolate coberto com coco ralado e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 49.00, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Romeu e Julieta', 'description' => 'goiabada coberta com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Sensacao', 'description' => 'chocolate com morango e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 52.00, 'category_id' => $categoryPizzaDocesId]
        ];

        foreach ($pizzasDoces as $pizza) {
            DB::table('products')->insert($pizza);
        }

        $categoryBebidasId = DB::table('categories')->where('name', 'Bebidas')->value('id');

        $bebidas = [
            ['name' => 'Coca Cola 2L', 'description' => 'Refrigerante Coca Cola 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 16.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Coca Cola Zero 2L', 'description' => 'Refrigerante Coca Cola Zero 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 16.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Dolly Guaraná 2L', 'description' => 'Refrigerante Dolly Guaraná 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 10.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Dolly Laranja 2L', 'description' => 'Refrigerante Dolly Laranja 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 10.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Dolly Limão 2L', 'description' => 'Refrigerante Dolly Limão 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 10.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Fanta Laranja 2L', 'description' => 'Refrigerante Fanta Laranja 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 14.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Fanta Uva 2L', 'description' => 'Refrigerante Fanta Uva 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 14.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Guaraná Antarctica 2L', 'description' => 'Refrigerante Guaraná Antarctica 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 14.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Coca Cola 600ml', 'description' => 'Refrigerante Coca Cola 600ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 9.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Coca Cola Zero 600ml', 'description' => 'Refrigerante Coca Cola Zero 600ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 9.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Guaraná Antarctica 600ml', 'description' => 'Refrigerante Guaraná Antarctica 600ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 8.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Coca Cola Lata', 'description' => 'Refrigerante Coca Cola lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 6.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Coca Cola Zero Lata', 'description' => 'Refrigerante Coca Cola Zero lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 6.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Fanta Laranja Lata', 'description' => 'Refrigerante Fanta Laranja lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 6.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Fanta Uva Lata', 'description' => 'Refrigerante Fanta Uva lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 6.00, 'category_id' => $categoryBebidasId],
            ['name' => 'Guaraná Antarctica Lata', 'description' => 'Refrigerante Guaraná Antarctica lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 6.00, 'category_id' => $categoryBebidasId]

        ];

        foreach ($bebidas as $bebida) {
            DB::table('products')->insert($bebida);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
