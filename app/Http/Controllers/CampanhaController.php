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
        $this->middleware('roles:coordenador|professor|admin|estudante')
             ->only(['show', 'indexPorCurso','estudantes']);
    
        // 3. Restante dos métodos: Apenas cargos administrativos
        $this->middleware('roles:coordenador|admin')
             ->only(['index']);
    }

public function cursoCampanhaCreate(Curso $curso){
    return view('app.private.dashboard.othes.cursos.campanhaCreate', compact( 'curso'));
}
public function indexPorCurso(Curso $curso)
{
    try{
            // Carrega as campanhas apenas deste curso com paginação
    $campanhas = Campanha::where('curso_id', $curso->id)
        ->with('curso.departamento')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('app.private.dashboard.othes.cursos.campanhas', compact('campanhas', 'curso'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
}

public function desaEstudante($campanha_id,$estudante_id)
{
    try{
    $estudante=Estudante::findOrFail($estudante_id);
    if(!$estudante->anteprojetos->isEmpty()){
        return redirect()->back()->with('error','Não é possivel elminar o estudante desta campanha, já tem anteprojeto carregado!');
    }

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
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }

}
    public function index()
    {
        try{
      //  dd("xddgdgd");
        $campanhas = Campanha::with([
            'curso.departamento',
            'coordenador.professor.user'
        ])->paginate(10);

        return view('app.private.dashboard.othes.campanhas.index', compact('campanhas'));

        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
public function show($id)
{
    try{
    $campanha = Campanha::with([
        'curso',
        'coordenador.professor.user'
    ])->findOrFail($id);

    return view('app.private.dashboard.othes.campanhas.show', compact('campanha'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }

public function estudantes($id)
{
    try{
    $campanha = Campanha::with([
        'curso',
        'coordenador.professor.user'
    ])->findOrFail($id);
    $estudantes=$campanha->estudantes()->paginate(10);
    return view('app.private.dashboard.othes.campanhas.estudantes', compact('campanha','estudantes'));
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
    }
    public function create()
    {
       try{
        $cursos = Curso::with('departamento.coordenador')->get();

        return view('app.private.dashboard.othes.campanhas.create', compact('cursos'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }
 
public function addEstudante(Request $request, $campanha_id)
{
try{
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

/*$existe = DB::table('campanha_estudante')
->where('estudante_id',$estudante->id)
->exists();
*/
$existe = $estudante->campanhas()
    ->where('status', 'aberta') // ou 'ativa', mude para o termo que usa no banco
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
} catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
}
public function updatePrazos(Request $request, Campanha $campanha)
{
    try{
    $request->validate([
        'prazo_submissao' => 'required|date',
        'prazo_melhorias' => 'nullable|date',
    ]);

    $campanha->update([
        'prazo_submissao' => $request->prazo_submissao,
        'prazo_melhorias' => $request->prazo_melhorias,
    ]);

    return redirect()->back()->with('success', 'Prazos atualizados com sucesso!');
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
}
 
    public function store(Request $request)
    {
         try {
        $request->validate([

    'curso_id' => [
        'required',
        'exists:cursos,id'
    ],

    'titulo' => [
        'required',
        'string',
        'min:10',
        'max:255',
        'regex:/^[A-Za-zÀ-ÿ0-9\s\-\_\.\,\(\)]+$/u'
    ],

    'descricao' => [
        'required',
        'string',
        'min:20',
        'max:5000'
    ],

    'prazo_submissao' => [
        'required',
        'date',
        'after:today'
    ],

], [

    'curso_id.required' => 'Selecione um curso.',
    'curso_id.exists' => 'O curso selecionado é inválido.',

    'titulo.required' => 'O título da campanha é obrigatório.',
    'titulo.min' => 'O título deve ter no mínimo 10 caracteres.',
    'titulo.max' => 'O título não pode ultrapassar 255 caracteres.',
    'titulo.regex' => 'O título contém caracteres inválidos.',

    'descricao.required' => 'A descrição é obrigatória.',
    'descricao.min' => 'A descrição deve ter no mínimo 20 caracteres.',
    'descricao.max' => 'A descrição não pode ultrapassar 5000 caracteres.',

    'prazo_submissao.required' => 'Informe o prazo de submissão.',
    'prazo_submissao.date' => 'Informe uma data válida.',
    'prazo_submissao.after' => 'A data deve ser superior à data atual.',

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
   // dd($e->getMessage());
            return back()->with('error','Erro ao criar campanha.'.$e->getMessage());
        }
        
    }

    public function edit(Campanha $campanha)
    {
        $cursos = Curso::all();

        return view('app.private.dashboard.othes.campanhas.edit', compact('campanha','cursos'));
    }

    public function update(Request $request, Campanha $campanha)
    {
        try{
        $request->validate([

            'titulo' => [
                'required',
                'string',
                'min:10',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ0-9\s\-\_\.\,\(\)]+$/u'
            ],

            'descricao' => [
                'required',
                'string',
                'min:10',
                'max:5000'
            ],

            'prazo_submissao' => [
                'required',
                'date',
                'after:today'
            ],

            'prazo_melhorias' => [
                'nullable',
                'date',
                'after:prazo_submissao'
            ],

          
            'status' => [
                'required',
                'in:aberta,encerrada,cancelada'
            ]

        ], [

            'titulo.required' => 'O título é obrigatório.',
            'titulo.min' => 'O título deve ter no mínimo 10 caracteres.',
            'titulo.max' => 'O título não pode ultrapassar 255 caracteres.',
            'titulo.regex' => 'O título contém caracteres inválidos.',

            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.min' => 'A descrição deve ter no mínimo 10 caracteres.',

            'prazo_submissao.required' => 'O prazo de submissão é obrigatório.',
            'prazo_submissao.after' => 'A data de submissão deve ser superior à data atual.',

            'prazo_melhorias.after' => 'O prazo de melhorias deve ser posterior ao prazo de submissão.',

            'prazo_novo.after' => 'O prazo novo deve ser posterior ao prazo de melhorias.',

            'status.required' => 'Selecione o status da campanha.'

        ]);

        DB::beginTransaction();

        

            $campanha->update($request->all());

            DB::commit();

            return redirect()->route('cursos.campanhas',$campanha->curso_id)
                ->with('success','Campanha atualizada.');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error','Erro ao atualizar : '.$e->getMessage());
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

            return back()->with('error','Erro ao eliminar : '.$e->getMessage());
        }
    }
}