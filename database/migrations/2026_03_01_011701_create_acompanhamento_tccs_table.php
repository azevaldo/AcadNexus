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
    Schema::create('acompanhamento_tcc', function (Blueprint $table) {
        $table->id();
        $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();
        $table->foreignId('tutor_id')->constrained('tutores');
        $table->foreignId('co_tutor_id')->nullable()->constrained('tutores');
        $table->foreignId('anteprojeto_id')->constrained('anteprojetos')->cascadeOnDelete();
        $table->enum('status', ['ativo','finalizado']); 
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acompanhamento_tccs');
    }
};
