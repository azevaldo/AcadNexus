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
        Schema::create('coordenadores', function (Blueprint $table) {
    $table->id();
    $table->foreignId('departamento_id')->constrained()->cascadeOnDelete();
    $table->foreignId('professor_id')->constrained('professores')->cascadeOnDelete();
    $table->boolean('ativo')->default(true);
     $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coordenadors');
    }
};
