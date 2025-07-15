<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            // Ligação com motoboy
            $table->foreignId('motoboy_id')->nullable()->constrained()->onDelete('set null');

            // Observação do pedido (ex: sem cebola, entrega atrasada)
            $table->text('observation')->nullable();

            // Troco para quanto?
            $table->decimal('change_for', 10, 2)->nullable();
            // Taxa de entrega
            $table->decimal('delivery_fee', 10, 2)->default(0);

            $table->string('status_id')->default('1'); // Status do pedido
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

