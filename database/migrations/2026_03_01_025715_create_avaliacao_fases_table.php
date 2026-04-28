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
        Schema::create('avaliacao_fases', function (Blueprint $table) {
             $table->id();
    $table->foreignId('submissao_fase_id')->constrained('submissao_fase')->cascadeOnDelete();
    $table->foreignId('tutor_id')->constrained('tutores');
    $table->enum('decisao', ['aprovado','ajustes']);
    $table->text('descricao');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacao_fases');
    }
};
