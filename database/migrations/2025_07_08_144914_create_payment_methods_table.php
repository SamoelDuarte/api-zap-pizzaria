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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // ✅ Insere os métodos padrão
        DB::table('payment_methods')->insert([
            ['name' => 'Dinheiro', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pix', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cartão de Crédito', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cartão de Débito', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
