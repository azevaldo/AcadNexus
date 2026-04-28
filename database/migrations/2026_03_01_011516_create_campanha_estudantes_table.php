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
        Schema::create('campanha_estudante', function (Blueprint $table) {
        $table->id();
        $table->foreignId('campanha_id')->constrained('campanhas')->cascadeOnDelete();
        $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campanha_estudantes');
    }
};
