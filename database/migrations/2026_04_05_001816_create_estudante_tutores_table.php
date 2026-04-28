<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     
    public function up(): void
    {
        Schema::create('estudante_tutores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudante_id')->constrained('estudantes')->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained('tutores');
            $table->foreignId('co_tutor_id')->nullable()->constrained('tutores');
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('estudante_tutores');
    }
};
