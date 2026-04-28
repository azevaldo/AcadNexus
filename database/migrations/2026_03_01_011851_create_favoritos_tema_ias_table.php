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
        Schema::create('favoritos_tema_ia', function (Blueprint $table) {
    $table->id();
    $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();
    $table->foreignId('sugestao_id')->constrained('sugestoes_tema_ia')->cascadeOnDelete();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoritos_tema_ias');
    }
};
