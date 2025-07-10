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
            ['name' => 'Alho', 'description' => 'Mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Americana', 'description' => 'Ovos e bacon coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Atum', 'description' => 'Atum com cebola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Atum com mussarela', 'description' => 'Atum e cebola coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Bacon', 'description' => 'Mussarela com bacon', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Baiana', 'description' => 'Calabresa, ovos, cebola e pimenta calabresa', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Baiana com mussarela', 'description' => 'Calabresa, ovos e cebola, coberto com mussarela e pimenta calabresa', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Bauru', 'description' => 'Presunto e tomates coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Bonança', 'description' => 'Frango, milho, calabresa, lombo, catupiry e cheddar coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Brócolis com catupiry', 'description' => 'Brócolis, bacon, catupiry e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Brócolis com mussarela', 'description' => 'Brócolis e bacon coberto com mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Caipira', 'description' => 'Frango, milho e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Caipira com mussarela', 'description' => 'Frango e milho coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Calabresa especial', 'description' => 'Calabresa e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Calabresa', 'description' => 'Calabresa e cebola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Calabria', 'description' => 'Calabresa, cebola e bacon coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Calamussa', 'description' => 'Calabresa assada coberta com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Camarão', 'description' => 'Camarão cozido com mussarela ou catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Canadense', 'description' => 'Lombo canadense com catupiry ou mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Carne seca', 'description' => 'Carne seca desfiada e cebola coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Carne seca especial', 'description' => 'Carne seca desfiada, cebola e catupiry coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Catumilho', 'description' => 'Milho coberto com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Champignon', 'description' => 'Champignon com mussarela ou catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Cinco queijos', 'description' => 'Mussarela, provolone, parmesão, catupiry e gorgonzola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Costela', 'description' => 'Costela desfiada, cebola, catupiry, coberto com mussarela e pimentão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Cremosa', 'description' => 'Presunto e catupiry coberto mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Crocante', 'description' => 'Frango desfiado e catupiry coberto com batata palha', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Dois queijos', 'description' => 'Mussarela e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Escarola 1', 'description' => 'Escarola coberto com mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Escarola 2', 'description' => 'Escarola, bacon e catupiry coberto com mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Ferrary', 'description' => 'Calabresa, cheddar e parmesão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Frango catupiry', 'description' => 'Frango desfiado com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Frango com cheddar', 'description' => 'Frango desfiado com cheddar', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Frango com mussarela', 'description' => 'Frango desfiado coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Frango original', 'description' => 'Frango desfiado com catupiry original', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Íntegra', 'description' => 'Frango desfiado, palmito, catupiry, mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Jardineira', 'description' => 'Frango, presunto, milho e ervilha coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Marguerita', 'description' => 'Mussarela, tomate, parmesão e manjericão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Milho', 'description' => 'Milho com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Moda da casa', 'description' => 'Carne seca e brócolis com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Moda do pizziola', 'description' => 'Peito de peru ralado e catupiry coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Mussarela Tradicional', 'description' => 'Mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Palmito', 'description' => 'Palmito coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Peito de peru com mussarela', 'description' => 'Peito de peru ralado e tomate coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Peperone', 'description' => 'Mussarela e rodelas de peperoni', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Portuguesa de peru', 'description' => 'Peito de peru ralado, milho, cebola e ovo coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Portuguesa', 'description' => 'Presunto, ervilha, ovos e cebola coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Quatro queijos', 'description' => 'Mussarela, provolone, parmesão e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Romanesca', 'description' => 'Presunto, bacon, champignon, catupiry e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Rúcula', 'description' => 'Mussarela, rúcula e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Tomate seco', 'description' => 'Mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Toscana', 'description' => 'Calabresa moída, mussarela, tomate e parmesão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Três queijos', 'description' => 'Mussarela, parmesão e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Vegetariana 1', 'description' => 'Escarola, ervilha, palmito, mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
            ['name' => 'Vegetariana 2', 'description' => 'Brócolis, milho, palmito, mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 33.99, 'category_id' => $categoryPizzaClassicasId],
        ];

        foreach ($pizzasClassicas as $pizza) {
            DB::table('products')->insert($pizza);
        }
        // Adicionar produtos para a categoria "Pizzas Broto"
        $categoryPizzaBrotoId = DB::table('categories')->where('name', 'Pizzas Broto')->value('id');

        $pizzasBroto =  [
            ['name' => 'Alho (Broto)', 'description' => 'Mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Americana (Broto)', 'description' => 'Ovos e bacon coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Atum (Broto)', 'description' => 'Atum com cebola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Atum com mussarela (Broto)', 'description' => 'Atum e cebola coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Bacon (Broto)', 'description' => 'Mussarela com bacon', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Baiana (Broto)', 'description' => 'Calabresa, ovos, cebola e pimenta calabresa', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Baiana com mussarela (Broto)', 'description' => 'Calabresa, ovos e cebola, coberto com mussarela e pimenta calabresa', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Bauru (Broto)', 'description' => 'Presunto e tomates coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Bonança (Broto)', 'description' => 'Frango, milho, calabresa, lombo, catupiry e cheddar coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Brócolis com catupiry (Broto)', 'description' => 'Brócolis, bacon, catupiry e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Brócolis com mussarela (Broto)', 'description' => 'Brócolis e bacon coberto com mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Caipira (Broto)', 'description' => 'Frango, milho e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Caipira com mussarela (Broto)', 'description' => 'Frango e milho coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Calabresa especial (Broto)', 'description' => 'Calabresa e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Calabresa (Broto)', 'description' => 'Calabresa e cebola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Calabria (Broto)', 'description' => 'Calabresa, cebola e bacon coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Calamussa (Broto)', 'description' => 'Calabresa assada coberta com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Camarão (Broto)', 'description' => 'Camarão cozido com mussarela ou catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Canadense (Broto)', 'description' => 'Lombo canadense com catupiry ou mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Carne seca (Broto)', 'description' => 'Carne seca desfiada e cebola coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Carne seca especial (Broto)', 'description' => 'Carne seca desfiada, cebola e catupiry coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Catumilho (Broto)', 'description' => 'Milho coberto com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Champignon (Broto)', 'description' => 'Champignon com mussarela ou catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Cinco queijos (Broto)', 'description' => 'Mussarela, provolone, parmesão, catupiry e gorgonzola', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Costela (Broto)', 'description' => 'Costela desfiada, cebola, catupiry, coberto com mussarela e pimentão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Cremosa (Broto)', 'description' => 'Presunto e catupiry coberto mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Crocante (Broto)', 'description' => 'Frango desfiado e catupiry coberto com batata palha', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Dois queijos (Broto)', 'description' => 'Mussarela e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Escarola 1 (Broto)', 'description' => 'Escarola coberto com mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Escarola 2 (Broto)', 'description' => 'escarola, bacon e catupiry coberto com mussarela e alho frito', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Ferrary (Broto)', 'description' => 'calabresa ,cheddar e parmesão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Frango catupiry (Broto)', 'description' => 'frango desfiado com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Frango com cheddar (Broto)', 'description' => 'frango desfiado com cheddar', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Frango com mussarela (Broto)', 'description' => 'frango desfiado coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Frango original (Broto)', 'description' => 'frango desfiado com catupiry original', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Íntegra (Broto)', 'description' => 'frango desfiado,palmito,catupiry, mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Jardineira (Broto)', 'description' => 'frango, presunto,milho e ervilha coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Marguerita (Broto)', 'description' => 'mussarela,tomate,parmesão e manjericão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Milho (Broto)', 'description' => 'milho com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Moda da casa (Broto)', 'description' => 'carne seca e brócolis com catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Moda do pizziola (Broto)', 'description' => 'peito de peru ralado e catupiry coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Mussarela (Broto)', 'description' => 'mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Palmito (Broto)', 'description' => 'palmito coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Peito de peru com mussarela (Broto)', 'description' => 'peito de peru ralado e tomate coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Peperone (Broto)', 'description' => 'mussarela e rodelas de peperoni', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Portuguesa de peru (Broto)', 'description' => 'peito de peru ralado ,milho ,cebola e ovo coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Portuguesa (Broto)', 'description' => 'presunto,ervilha,ovos e cebola coberto com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Quatro queijos (Broto)', 'description' => 'mussarela ,provolone,parmesão e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Romanesca (Broto)', 'description' => 'presunto,bacon, champignon, catupiry e mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Rúcula (Broto)', 'description' => 'mussarela,rúcula e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Tomate seco (Broto)', 'description' => 'mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Toscana (Broto)', 'description' => 'calabresa moída ,mussarela, tomate e parmesão', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Três queijos (Broto)', 'description' => 'mussarela, parmesão e catupiry', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Vegetariana 1 (Broto)', 'description' => 'escarola,ervilha ,palmito,mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
            ['name' => 'Vegetariana 2 (Broto)', 'description' => 'brócolis,milho,palmito,mussarela e tomate seco', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaBrotoId],
        ];

        foreach ($pizzasBroto as $pizza) {
            DB::table('products')->insert($pizza);
        }

        // Adicionar produtos para a categoria "Pizzas Broto"
        $categoryPizzaDocesBrotoId = DB::table('categories')->where('name', 'Pizzas Doces Broto')->value('id');

        $pizzasDocesBroto = [
            ['name' => 'Banamussa (Broto)', 'description' => 'banana coberta com mussarela, leite condensado e canela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
            ['name' => 'Banela (Broto)', 'description' => 'banana, leite condensado e canela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
            ['name' => 'Brigadeiro (Broto)', 'description' => 'chocolate coberto com granulado e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
            ['name' => 'Chocolate (Broto)', 'description' => 'chocolate', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
            ['name' => 'Choconana (Broto)', 'description' => 'banana com chocolate', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
            ['name' => 'Chocouva (Broto)', 'description' => 'uva verde com chocolate e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
            ['name' => 'Confette (Broto)', 'description' => 'chocolate coberto com confette e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
            ['name' => 'Prestigio (Broto)', 'description' => 'chocolate coberto com coco ralado e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
            ['name' => 'Romeu e Julieta (Broto)', 'description' => 'goiabada coberta com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
            ['name' => 'Sensacao (Broto)', 'description' => 'chocolate com morango e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 26.99, 'category_id' => $categoryPizzaDocesBrotoId],
        ];

        foreach ($pizzasDocesBroto as $pizza) {
            DB::table('products')->insert($pizza);
        }

         // Adicionar produtos para a categoria "Pizzas Broto"
        $categoryPizzaDocesId = DB::table('categories')->where('name', 'Pizzas Doces')->value('id');

        $pizzasDoces = [
            ['name' => 'Banamussa', 'description' => 'banana coberta com mussarela, leite condensado e canela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Banela', 'description' => 'banana, leite condensado e canela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Brigadeiro', 'description' => 'chocolate coberto com granulado e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Chocolate', 'description' => 'chocolate', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Choconana', 'description' => 'banana com chocolate', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Chocouva', 'description' => 'uva verde com chocolate e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Confette', 'description' => 'chocolate coberto com confette e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Prestigio', 'description' => 'chocolate coberto com coco ralado e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Romeu e Julieta', 'description' => 'goiabada coberta com mussarela', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
            ['name' => 'Sensacao', 'description' => 'chocolate com morango e leite condensado', 'image' => 'assets/imagens/img_pizza_padrao.png', 'price' => 42.99, 'category_id' => $categoryPizzaDocesId],
        ];

        foreach ($pizzasDoces as $pizza) {
            DB::table('products')->insert($pizza);
        }

        $categoryBebidasId = DB::table('categories')->where('name', 'Bebidas')->value('id');
        $bebidas = [
            // Refrigerantes 2 Litros
            ['name' => 'Coca Cola 2L', 'description' => 'Refrigerante Coca Cola 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 12.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Coca Cola Zero 2L', 'description' => 'Refrigerante Coca Cola Zero 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 12.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Dolly Guaraná 2L', 'description' => 'Refrigerante Dolly Guaraná 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 12.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Dolly Laranja 2L', 'description' => 'Refrigerante Dolly Laranja 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 12.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Dolly Limão 2L', 'description' => 'Refrigerante Dolly Limão 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 12.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Fanta Laranja 2L', 'description' => 'Refrigerante Fanta Laranja 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 12.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Fanta Uva 2L', 'description' => 'Refrigerante Fanta Uva 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 12.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Guaraná Antarctica 2L', 'description' => 'Refrigerante Guaraná Antarctica 2 litros', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 12.99, 'category_id' => $categoryBebidasId],

            // Refrigerantes 600ml
            ['name' => 'Coca Cola 600ml', 'description' => 'Refrigerante Coca Cola 600ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 9.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Coca Cola Zero 600ml', 'description' => 'Refrigerante Coca Cola Zero 600ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 9.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Guaraná Antarctica 600ml', 'description' => 'Refrigerante Guaraná Antarctica 600ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 9.99, 'category_id' => $categoryBebidasId],

            // Refrigerante Lata
            ['name' => 'Coca Cola Lata', 'description' => 'Refrigerante Coca Cola lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 7.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Coca Cola Zero Lata', 'description' => 'Refrigerante Coca Cola Zero lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 7.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Fanta Laranja Lata', 'description' => 'Refrigerante Fanta Laranja lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 7.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Fanta Uva Lata', 'description' => 'Refrigerante Fanta Uva lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 7.99, 'category_id' => $categoryBebidasId],
            ['name' => 'Guaraná Antarctica Lata', 'description' => 'Refrigerante Guaraná Antarctica lata 350ml', 'image' => 'assets/imagens/img_bebida_padrao.png', 'price' => 7.99, 'category_id' => $categoryBebidasId],
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
