<?php

namespace App\Http\Controllers;

use App\Models\Campanha_estudante;
use Illuminate\Http\Request;
use App\Models\Campanha;
use Illuminate\Support\Facades\Auth;

class CampanhaEstudanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
    {
      //  dd("xddgdgd");
      $user=Auth::user();
      $campanhas =$user->estudante->campanhas()->paginate(10);
          
//dd("xddgdgd");
        return view('app.private.dashboard.estudantes.campanhas.index', compact('campanhas'));
    }
public function show($id)
{
    $campanha = Campanha::with([
        'curso',
        'coordenador.professor.user'
    ])->findOrFail($id);

    return view('app.private.dashboard.estudantes.campanhas.show', compact('campanha'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campanha_estudante $campanha_estudante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campanha_estudante $campanha_estudante)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campanha_estudante $campanha_estudante)
    {
        //
    }
}
