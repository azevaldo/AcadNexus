<?php

 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SugestaoTemaIa;
use App\Models\Estudante;
use App\Models\FavoritoTemaIa;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Opcional: Requer pacote barryvdh/laravel-dompdf

class RelatorioEstudanteController extends Controller
{
   public function index()
{
    try{
    $user = Auth::user();
    // Eager loading para performance
    $estudante = Estudante::with(['curso', 'anteprojetos', 'tutorRelacao.tutor.user', 'campanhas'])
        ->where('user_id', $user->id)
        ->first();

    // Estatísticas para os Cards Superiores
    $stats = [
        'anteprojetos_contagem' => $estudante->anteprojetos->count(),
        'temas_ia' => \App\Models\SugestaoTemaIa::where('estudante_id', $estudante->id)->count(),
        'favoritos_ia' => \App\Models\FavoritoTemaIa::where('estudante_id', $estudante->id)->count(),
        'campanhas_ativas' => $estudante->campanhas()->where('status','aberta')->count(),
    ];

    return view('app.private.dashboard.estudantes.relatorios.index', compact('estudante', 'stats'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

         

    public function exportarPdf()
    {
        try{
        $estudante = Auth::user()->estudante;
        $temas = SugestaoTemaIa::where('estudante_id', $estudante->id)->with('area')->get();

        // Se tiver o DomPDF instalado, descomente:
        // $pdf = Pdf::loadView('app.private.dashboard.estudantes.relatorios.pdf', compact('temas', 'estudante'));
        // return $pdf->download('meus-temas-tcc.pdf');
        
        return back()->with('info', 'Funcionalidade de PDF requer instalação do DomPDF.');
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }
}