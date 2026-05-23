<?php

namespace App\Http\Controllers;

use App\Models\EstudanteTutor;
use App\Models\Professor;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorController extends Controller
{
     
    public function index()
    {
        // Recupera o tutor através do usuário logado (User -> Professor -> Tutor)
     /*   

        if (!$tutor) {
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a orientadores.');
        }
*/
        $tutor = Auth::user()->professor;
        // Busca estudantes onde o professor logado é Tutor Principal OU Co-Tutor
        $orientandos = EstudanteTutor::with(['estudante.user', 'estudante.curso', 'tutor.user', 'coTutor.user'])
            ->where('tutor_id', $tutor->id)
            ->orWhere('co_tutor_id', $tutor->id)
            ->get();

        return view('app.private.dashboard.othes.tutores.estudantes', compact('orientandos', 'tutor'));
    }
}