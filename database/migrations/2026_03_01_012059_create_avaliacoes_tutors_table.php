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
        Schema::create('avaliacoes_tutor', function (Blueprint $table) {
    $table->id();
    $table->foreignId('professor_id')->constrained('professores')->cascadeOnDelete();
    $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();
    $table->integer('nota');
    $table->text('comentario')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacoes_tutors');
    }
};
