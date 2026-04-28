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
        Schema::create('solicitacoes_troca_tema', function (Blueprint $table) {
    $table->id();
    $table->foreignId('estudante_id')->constrained()->cascadeOnDelete();
    $table->string('tema_atual');
    $table->string('tema_proposto');
    $table->text('justificativa');
    $table->string('documento');
    $table->enum('status', ['pendente','aprovado','rejeitado']);
    $table->foreignId('diretor_id')->constrained('users');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes_troca_temas');
    }
};
