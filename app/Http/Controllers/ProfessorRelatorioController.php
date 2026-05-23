<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Support\Facades\Auth;

class ProfessorRelatorioController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $professor = Professor::with([
            'user',
            'departamentos',
            'areas',
            'estudantes.estudante.user',
            'estudantes.estudante.curso',
            'coorientados.estudante.user',
            'acompanhamentos'
        ])->where('user_id', $user->id)->firstOrFail();

        // Estatísticas para o Dashboard do Professor
        $stats = [
            'total_orientandos' => $professor->estudantes->count(),
            'total_coorientandos' => $professor->coorientados->count(),
            'total_acompanhamentos' => $professor->acompanhamentos->count(),
            'total_areas' => $professor->areas->count(),
        ];
 
        return view('app.private.dashboard.othes.professores.relatorios.index', compact('professor', 'stats'));
    }
}