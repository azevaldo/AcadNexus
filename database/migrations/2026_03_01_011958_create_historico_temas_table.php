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
        Schema::create('historico_temas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('estudante_id')->constrained()->cascadeOnDelete();
    $table->string('tema');
    $table->enum('acao', ['criado','alterado','aprovado','rejeitado']);
    $table->text('observacao')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_temas');
    }
};
