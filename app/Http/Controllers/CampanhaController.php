<?php

namespace App\Http\Controllers;

use App\Models\Campanha;
use App\Models\Curso;
use App\Models\Coordenador;
use App\Models\Estudante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CampanhaController extends Controller
{
public function __construct()
    {
        // 1. Garantir que o usuário esteja logado
        $this->middleware('auth');

        // 2. Index e Show: Acessíveis por todos os cargos mencionados
        $this->middleware('roles:coordenador|professor|secretaria|admin|estudante')
             ->only(['index', 'show', 'indexPorCurso','estudantes']);
    
        // 3. Restante dos métodos: Apenas cargos administrativos
        $this->middleware('roles:coordenador|admin|secretaria')
             ->except(['index', 'show', 'indexPorCurso','estudantes']);
    }
public function cursoCampanhaCreate(Curso $curso){
    return view('app.private.dashboard.othes.cursos.campanhaCreate', compact( 'curso'));
}
public function indexPorCurso(Curso $curso)
{
    // Carrega as campanhas apenas deste curso com paginação
    $campanhas = Campanha::where('curso_id', $curso->id)
        ->with('curso.departamento')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('app.private.dashboard.othes.cursos.campanhas', compact('campanhas', 'curso'));
}

public function desaEstudante($campanha_id,$estudante_id)
{

$existe = DB::table('campanha_estudante')
->where('campanha_id',$campanha_id)
->where('estudante_id',$estudante_id)
->exists();


if(!$existe){

return back()->withErrors([
'erro'=>'Este estudante não está associado a esta campanha.'
]);

}


DB::table('campanha_estudante')
->where('campanha_id',$campanha_id)
->where('estudante_id',$estudante_id)
->delete();


return back()->with('success','Estudante desassociado da campanha com sucesso.');

}
    public function index()
    {
      //  dd("xddgdgd");
        $campanhas = Campanha::with([
            'curso.departamento',
            'coordenador.professor.user'
        ])->paginate(10);
//dd("xddgdgd");
        return view('app.private.dashboard.othes.campanhas.index', compact('campanhas'));
    }
public function show($id)
{
    $campanha = Campanha::with([
        'curso',
        'coordenador.professor.user'
    ])->findOrFail($id);

    return view('app.private.dashboard.othes.campanhas.show', compact('campanha'));
}

public function estudantes($id)
{
    $campanha = Campanha::with([
        'curso',
        'coordenador.professor.user'
    ])->findOrFail($id);
    $estudantes=$campanha->estudantes()->paginate(10);
    return view('app.private.dashboard.othes.campanhas.estudantes', compact('campanha','estudantes'));
}
    public function create()
    {
       
        $cursos = Curso::with('departamento.coordenador')->get();

        return view('app.private.dashboard.othes.campanhas.create', compact('cursos'));
    }
 
public function addEstudante(Request $request, $campanha_id)
{

$request->validate([
'email' => ['required','email']
]);

$campanha = Campanha::findOrFail($campanha_id);


/*
BUSCAR ESTUDANTE PELO EMAIL
*/
$estudante = Estudante::whereHas('user', function($q) use ($request){
$q->where('email',$request->email);
})->first();


if(!$estudante){

return back()->with('error','Nenhum estudante encontrado com esse email.'
);

}


/*
VERIFICAR SE O CURSO É IGUAL
*/

if($estudante->curso_id != $campanha->curso_id){

return back()->with('error',
 'Este estudante não pertence ao curso desta campanha.'
);

}


/*
VERIFICAR SE JÁ ESTÁ EM UMA CAMPANHA
*/

$existe = DB::table('campanha_estudante')
->where('estudante_id',$estudante->id)
->exists();


if($existe){

return back()-> with('error','Este estudante já está associado a outra campanha.'
 );

}


/*
ASSOCIAR
*/

DB::table('campanha_estudante')->insert([

'campanha_id' => $campanha->id,
'estudante_id' => $estudante->id,
'created_at' => now(),
'updated_at' => now()

]);


return back()->with('success','Estudante adicionado à campanha com sucesso.');

}
public function updatePrazos(Request $request, Campanha $campanha)
{
    $request->validate([
        'prazo_submissao' => 'required|date',
        'prazo_melhorias' => 'nullable|date',
    ]);

    $campanha->update([
        'prazo_submissao' => $request->prazo_submissao,
        'prazo_melhorias' => $request->prazo_melhorias,
    ]);

    return redirect()->back()->with('success', 'Prazos atualizados com sucesso!');
}
 
    public function store(Request $request)
    {
         try {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prazo_submissao' => 'nullable|date',
           
        ]);

        DB::beginTransaction();

       

            $curso = Curso::with('departamento.coordenador')
                ->findOrFail($request->curso_id);

            $coordenador = $curso->departamento->coordenador;

            if (!$coordenador) {
               
                return back()->with('error',
                    'Este departamento não possui coordenador ativo.'
                );
            }

            Campanha::create([
                'curso_id' => $request->curso_id,
                'coordenador_id' => $coordenador->id,
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'prazo_submissao' => $request->prazo_submissao,
                'status' => 'aberta'
            ]);

            DB::commit();

            return redirect()->route('cursos.campanhas',$request->curso_id)
                ->with('success','Campanha criada com sucesso.');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());
    dd($e->getMessage());
            return back()->withErrors('Erro ao criar campanha.');
        }
    }

    public function edit(Campanha $campanha)
    {
        $cursos = Curso::all();

        return view('app.private.dashboard.othes.campanhas.edit', compact('campanha','cursos'));
    }

    public function update(Request $request, Campanha $campanha)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prazo_submissao' => 'nullable|date',
            'prazo_melhorias' => 'nullable|date',
            'prazo_novo' => 'nullable|date',
            'status' => 'required|in:aberta,encerrada'
        ]);

        DB::beginTransaction();

        try {

            $campanha->update($request->all());

            DB::commit();

            return redirect()->route('campanhas.index')
                ->with('success','Campanha atualizada.');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return back()->withErrors('Erro ao atualizar.');
        }
    }

    public function destroy(Campanha $campanha)
    {
        DB::beginTransaction();

        try {

            $campanha->delete();

            DB::commit();

            return back()->with('success','Campanha eliminada.');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return back()->withErrors('Erro ao eliminar.');
        }
    }
}