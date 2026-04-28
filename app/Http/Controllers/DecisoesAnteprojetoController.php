<?php

namespace App\Http\Controllers;

use App\Models\DecisoesAnteprojeto;
use Illuminate\Http\Request;
 use App\Models\Anteprojeto;
use App\Models\DecisaoAnteprojeto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DecisoesAnteprojetoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   

public function storeDecisao(Request $request, $id)
{
    $request->validate([
        'decisao' => 'required|in:aprovado,rejeitado,melhorias',
        'mensagem' => 'required|string|min:10',
    ]);

    $anteprojeto = Anteprojeto::findOrFail($id);

    DB::transaction(function () use ($request, $anteprojeto) {
        // 1. Criar o registro da decisão
        DecisaoAnteprojeto::create([
            'anteprojeto_id' => $anteprojeto->id,
            'coordenador_id' => Auth::user()->coordenador->id, // Assume que o user logado é coordenador
            'decisao' => $request->decisao,
            'mensagem' => $request->mensagem,
        ]);

        // 2. Atualizar o status principal do anteprojeto
        $anteprojeto->update([
            'status' => $request->decisao
        ]);
    });

    return back()->with('success', 'Avaliação registada com sucesso!');
}

    /**
     * Display the specified resource.
     */
    public function show(DecisoesAnteprojeto $decisoesAnteprojeto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DecisoesAnteprojeto $decisoesAnteprojeto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DecisoesAnteprojeto $decisoesAnteprojeto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DecisoesAnteprojeto $decisoesAnteprojeto)
    {
        //
    }
}
