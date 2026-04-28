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
       Schema::create('decisoes_anteprojeto', function (Blueprint $table) {
    $table->id();
    $table->foreignId('anteprojeto_versao_id')->constrained('anteprojeto_versoes')->cascadeOnDelete();
    $table->foreignId('coordenador_id')->constrained('coordenadores');
    $table->enum('decisao', ['aprovado', 'rejeitado', 'melhorias']);
    $table->text('mensagem'); // Aqui vai o que precisa melhorar ou o porquê da rejeição
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisoes_anteprojetos');
    }
};
