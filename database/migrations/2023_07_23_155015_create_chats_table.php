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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('jid', 255)->nullable();
            $table->integer('erro')->default(0);

            // Dispositivo usado para atendimento (bot ou atendente)
            $table->unsignedBigInteger('session_id')->nullable();
            $table->foreign('session_id')->references('id')->on('devices')->onDelete('set null');

            $table->string('service_id', 255)->nullable();

            // Status geral do atendimento humano
            $table->string('await_answer', 255)->nullable();

            // Status do fluxo do pedido (funil do bot)
            $table->string('flow_stage', 100)->default('aguardando'); // NOVO CAMPO

            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
