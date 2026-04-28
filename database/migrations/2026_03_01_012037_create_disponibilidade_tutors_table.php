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
        Schema::create('disponibilidade_tutor', function (Blueprint $table) {
    $table->id();
    $table->foreignId('professor_id')->constrained('professores')->cascadeOnDelete();
    $table->integer('max_orientacoes');
    $table->integer('orientacoes_ativas')->default(0);
    $table->boolean('ativo')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibilidade_tutors');
    }
};
