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
        Schema::create('anteprojeto_versoes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('anteprojeto_id')->constrained('anteprojetos')->onDelete('cascade');
    $table->string('arquivo');
    $table->text('descricao')->nullable(); 
    $table->integer('numero_versao')->default(1);
    $table->enum('status', ['submetido', 'aprovado', 'rejeitado', 'melhorias'])->default('submetido');
    $table->boolean('lida')->default(false);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anteprojeto_versoes');
    }
};
