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
    Schema::create('estudantes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('curso_id')->constrained()->cascadeOnDelete();
    $table->string('numero_matricula')->unique();
     $table->string('bi')->unique();
      $table->integer('ano_frequencia');
    $table->enum('regime',['Regular','Pós Laboral'])->default("Regular");
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudantes');
    }
};
