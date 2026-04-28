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

        Schema::create('anteprojetos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('campanha_id')->constrained('campanhas')->cascadeOnDelete();
    $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();
    $table->string('titulo'); // Importante para identificar o tema
    $table->enum('status', ['submetido', 'aprovado', 'rejeitado', 'melhorias'])->default('submetido');
    $table->timestamps();
});
 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ante_projetos');
    }
};
