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
        Schema::create('fases_tcc', function (Blueprint $table) {
    $table->id();
    $table->foreignId('acompanhamento_id')->constrained('acompanhamento_tcc')->cascadeOnDelete();
    $table->string('fase');
    $table->text('descricao')->nullable();
    $table->integer('mes_inicio')->default(1); 
    $table->integer('mes_fim')->default(1);
    $table->enum('status', ['pendente','submetido','aprovado','ajustes']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fases__tccs');
    }
};
