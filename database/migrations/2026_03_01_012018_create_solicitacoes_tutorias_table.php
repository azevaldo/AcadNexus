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
        Schema::create('solicitacoes_tutoria', function (Blueprint $table) {
    $table->id();
    $table->foreignId('estudante_id')->constrained()->cascadeOnDelete();
    $table->foreignId('professor_id')->constrained('professores')->cascadeOnDelete();
    $table->enum('tipo', ['tutor','co_tutor']);
    $table->enum('origem', ['manual','recomendacao']);
    $table->enum('status', ['pendente','aceite','recusado']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes_tutorias');
    }
};
