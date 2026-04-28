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
        Schema::create('sugestoes_tema_ia', function (Blueprint $table) {
    $table->id();
    $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();
    $table->foreignId('area_id')->constrained('areas_cientificas');
    $table->json('disciplinas');
    $table->json('competencias');
    $table->enum('tipo_projeto', ['teorico','pratico']);
    $table->string('titulo');
    $table->text('descricao');
    $table->timestamps();
}); }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sugestoes_tema_ias');
    }
};
