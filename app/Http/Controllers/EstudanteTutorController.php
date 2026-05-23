<?php

namespace App\Http\Controllers;

use App\Models\Estudante;
use App\Models\Tutor;
use App\Models\EstudanteTutor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstudanteTutorController extends Controller
{
    public function vincularTutores(Request $request, $estudante_id)
    {
        try{
        $request->validate([
            'tutor_id' => 'required|exists:professores,id',
           // 'co_tutor_id' => 'nullable|exists:tutores,id|different:tutor_id',
            'co_tutor_id' => 'nullable|exists:professores,id',
        ], [
            'different' => 'O co-tutor não pode ser a mesma pessoa que o tutor principal.'
        ]);

        EstudanteTutor::updateOrCreate(
            ['estudante_id' => $estudante_id],
            [
                'tutor_id' => $request->tutor_id,
                'co_tutor_id' => $request->co_tutor_id
            ]
        );

        return back()->with('success', 'Orientadores atribuídos com sucesso!');
         } catch(Exception $e){
       
           // dd($e->getMessage());
            DB::rollBack();
            Log::error('Erro ao criar estudante: '.$e->getMessage());
    dd($e->getMessage());
            return back()->with('error','Erro ao criar estudante'.$e->getMessage())
                         ->withInput();
        }
    }

    public function desvincular(Request $request, $estudante_id)
{
    try{
    $relacao = EstudanteTutor::where('estudante_id', $estudante_id)->firstOrFail();
    $tipo = $request->input('tipo'); // 'tutor' ou 'co_tutor'
 
    if ($tipo === 'tutor') {
        // Remover tudo, pois não há orientação sem tutor principal
        $relacao->delete();
        $mensagem = "Orientadores removidos com sucesso!";
    } else {
        // Remover apenas o co-tutor
        $relacao->update(['co_tutor_id' => null]);
        $mensagem = "Co-tutor removido com sucesso!";
    }

    return back()->with('success', $mensagem);
      return back()->with('success', 'Orientadores atribuídos com sucesso!');
         } catch(Exception $e){
       
           // dd($e->getMessage());
            DB::rollBack();
            Log::error('Erro ao criar estudante: '.$e->getMessage());
    dd($e->getMessage());
            return back()->with('error','Erro ao criar estudante'.$e->getMessage())
                         ->withInput();
        }
}
}