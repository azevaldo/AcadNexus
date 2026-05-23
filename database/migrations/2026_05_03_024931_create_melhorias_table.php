<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('itens_melhoria', function (Blueprint $table) {
            $table->id();
            $table->string('titulo'); // Ex: Formatação ABNT
            $table->text('descricao')->nullable(); // Ex: O arquivo não segue as normas...
          
            $table->timestamps();
        });
 
   Schema::create('item_melhoria_decisao', function (Blueprint $table) {
    $table->id();
    // Em vez de versao_anteprojeto_id, usamos o ID da decisão/parecer
    $table->foreignId('decisao_anteprojeto_id')->constrained('decisoes_anteprojeto')->onDelete('cascade');
    $table->foreignId('item_melhoria_id')->constrained('itens_melhoria')->onDelete('cascade');
    
    $table->text('observacao_adicional')->nullable(); 
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('item_melhoria_versao');
        Schema::dropIfExists('itens_melhoria');
    }
};