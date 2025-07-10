<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nome do produto ou combinação de sabores
            $table->text('description')->nullable(); // Descrição do produto ou combinação de sabores
            $table->decimal('price', 10, 2); // Preço do item (considerando apenas o maior preço no caso de vários sabores)
            $table->integer('quantity');
            $table->string('crust')->default('Tradicional')->nullable(); // Nome da borda
            $table->decimal('crust_price', 10, 2)->default(0.00)->nullable(); // Preço da borda
            $table->text('observation')->nullable(); // Observação do cliente para o primeiro sabor
            $table->decimal('total', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
