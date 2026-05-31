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
       Schema::create('submissao_fase', function (Blueprint $table) {
    $table->id();
    $table->foreignId('fase_tcc_id')->constrained('fases_tcc')->cascadeOnDelete();
    $table->text('descricao_estudante');
    $table->boolean('visto')->default(false);
    $table->string('arquivo')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissao_fases');
    }
};
