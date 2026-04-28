<?php

namespace App\Http\Controllers;

use App\Models\EstudanteTutor;
use App\Models\Professor;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorController extends Controller
{
    public function toggle($professor_id)
    {
        $professor = Professor::findOrFail($professor_id);
        
        // Verifica se já existe na tabela tutores
        $tutor = Tutor::where('professor_id', $professor_id)->first();

        if ($tutor) {
            $tutor->delete();
            $mensagem = "O professor deixou de ser tutor.";
        } else {
            Tutor::create(['professor_id' => $professor_id]);
            $mensagem = "Professor promovido a Tutor com sucesso!";
        }

        return redirect()->back()->with('success', $mensagem);
    }
    public function index()
    {
        // Recupera o tutor através do usuário logado (User -> Professor -> Tutor)
        $tutor = Auth::user()->professor?->tutor;

        if (!$tutor) {
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a orientadores.');
        }

        // Busca estudantes onde o professor logado é Tutor Principal OU Co-Tutor
        $orientandos = EstudanteTutor::with(['estudante.user', 'estudante.curso', 'tutor.professor.user', 'coTutor.professor.user'])
            ->where('tutor_id', $tutor->id)
            ->orWhere('co_tutor_id', $tutor->id)
            ->get();

        return view('app.private.dashboard.othes.tutores.estudantes', compact('orientandos', 'tutor'));
    }
}