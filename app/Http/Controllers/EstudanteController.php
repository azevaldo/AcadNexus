<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Estudante;
use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class EstudanteController extends Controller
{
public function __construct(){
     $this->middleware('roles:coordenador|admin')
            ->only(['edit', /*'show'*/ 'indexPorCurso','estudantes']);
}
   public function index()
    {
        return redirect()->route('dashboard')->with('error','Essa pagina deixou de existir!');
        try {

            $estudantes = Estudante::with(['user','curso'])
                ->latest()
                ->paginate(10);

            return view('app.private.dashboard.othes.estudantes.index',
                compact('estudantes'));

        } catch (Exception $e) {
//dd($e->getMessage());
            Log::error('Erro ao listar estudantes: '.$e->getMessage());
            return back()->with('error','Erro ao carregar estudantes'.$e->getMessage());
        }
    } 
public function show($id)
    {
        try{
        // Carrega o estudante com o usuário, curso e os orientadores ativos
        $estudante = Estudante::with([
            'user', 
            'curso', 
            'tutorRelacao.tutor.user', 
            'tutorRelacao.coTutor.user'
        ])->findOrFail($id);

        return view('app.private.dashboard.othes.estudantes.show', compact('estudante'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
        }
public function create_estudante()
    {
        try{
        $cursos = Curso::orderBy('nome')->get();

        return view('app.private.auth.register_estudante',
            compact('cursos'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
            }

    public function create()
    {
        try{
        $cursos = Curso::orderBy('nome')->get();

        return view('app.private.dashboard.othes.estudantes.create',
            compact('cursos'));
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
            }
 public function edit($id)
{
    try{
    // 1. Carrega o estudante com o usuário e o curso atual para descobrir o departamento
    $estudante = Estudante::with(['user', 'curso'])->findOrFail($id);
    
    // Busca todos os cursos para popular o select de troca de curso
    $cursos = Curso::all();

    // 2. Captura o departamento do curso atual do estudante sendo editado
    $departamentoId = $estudante->curso?->departamento_id;

    // 3. Busca os professores usando a relação correta N:N (belongsToMany)
    $tutores = \App\Models\Professor::with(['user'])
        ->when($departamentoId, function ($query) use ($departamentoId) {
            // Filtra os professores que pertencem ao departamento do curso do estudante
            // varrendo a tabela pivô 'departamento_professores' via relacionamento
            return $query->whereHas('departamentos', function ($subQuery) use ($departamentoId) {
                $subQuery->where('departamentos.id', $departamentoId);
            });
        })
        ->withCount(['estudantes as orientacoes_ativas_count' => function ($query) {
            // Conta as orientações que estão atualmente ativas na tabela pivô de tutoria
            $query->where('ativo', true);
        }])
        ->get();
       // dd($tutores);

    return view(
        'app.private.dashboard.othes.estudantes.edit',
        compact('estudante', 'cursos', 'tutores')
    );
    } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro: ' . $e->getMessage());
        }
}

    public function store(Request $request)
    {
        try {

   $request->validate([
    // Correção aqui: [A-Za-z...] em vez de A-Za-Z
    'name' => [
        'required', 
        'string', 
        'min:3', 
        'max:100', 
        'regex:/^[A-Za-zāáâãéêíóôõúçÁÉÍÓÚÇ\s]+$/u'
    ],
    
    'email' => 'required|email|unique:users,email',
    
    'password' => 'required|min:6|confirmed',
    
    'bi' => [
        'required',
        'string',
        'size:14',
        'regex:/^\d{9}[A-Z]{2}\d{3}$/i',
        'unique:estudantes,bi'
    ]
    ,
     'telefone' => [
        'required',
            ],
    
    'ano_frequencia' => 'required|in:4,5',
    
    'curso_id' => 'required|exists:cursos,id',
    
    'numero_matricula' => 'required|unique:estudantes,numero_matricula',
    
    'regime' => 'required|in:Regular,Pós Laboral'
], [
    'name.regex' => 'O campo nome deve conter apenas letras e espaços.',
    'bi.regex' => 'O formato do BI é inválido. Deve conter 9 números, seguidos de 2 letras e 3 números (Ex: 000000000LA000).',
    'bi.size' => 'O BI deve ter exatamente 14 caracteres.',
    'ano_frequencia.in' => 'O ano de frequência deve ser obrigatoriamente o 4º ou 5º ano.'
]);
            DB::beginTransaction();

            // 🔹 1️⃣ Criar usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                                'telefone' => $request->telefone,
                'password' => Hash::make($request->password)
            ]);

            // 🔹 2️⃣ Criar estudante vinculado
            
            Estudante::create([
                'user_id' => $user->id,
                'curso_id' => $request->curso_id,
                'numero_matricula' => $request->numero_matricula,
                'regime' => $request->regime,
                'bi' => $request->bi,
                'ano_frequencia' => $request->ano_frequencia

            ]);
$user-> assignRole('estudante');
Auth::login($user);
//dd("o que foi?");
            DB::commit();
return redirect()->route('dashboard')->with('success','Usuario registrado com sucesso!');
           // return redirect()->route('estudantes.index')
             //   ->with('success','Estudante criado com sucesso');

           } catch(Exception $e){
       
           // dd($e->getMessage());
            DB::rollBack();
            Log::error('Erro ao criar estudante: '.$e->getMessage());
//dd($e->getMessage());
            return back()->with('error','Erro ao criar estudante'.$e->getMessage())
                         ->withInput();
        }
    }

    public function update(Request $request, $id)
{
    try{
        

       $request->validate([
    // Nome válido (apenas letras e espaços)
     'name' => [
    'required',
    'string',
    'min:3',
    'max:100',
    'regex:/^[\p{L}\s]+$/u'
],
    
    // Email único, mas ignorando o ID do próprio usuário que está sendo editado
    'email' => [
        'required',
        'email',
        'unique:users,email,' . $estudante->user_id
    ],
     'telefone' => [
        'required',
            ],
    
    // BI validado (9 números + 2 letras + 3 números) ignorando o próprio registro
    'bi' => [
        'required',
        'string',
        'size:14',
        'regex:/^\d{9}[A-Z]{2}\d{3}$/i',
        'unique:estudantes,bi,' . $estudante->id
    ],
    
    // Ano de frequência restrito a 4 ou 5
    'ano_frequencia' => 'required|in:4,5',
    
    'curso_id' => 'required|exists:cursos,id',
    
    // Matrícula única, ignorando a matrícula atual deste estudante
    'numero_matricula' => [
        'required',
        'unique:estudantes,numero_matricula,' . $estudante->id
    ],
    
    'regime' => 'required|in:Regular,Pós Laboral'
], [
    // Mensagens de erro personalizadas amigáveis
    'name.regex' => 'O campo nome deve conter apenas letras e espaços.',
    'bi.regex' => 'O formato do BI é inválido. Deve conter 9 números, seguidos de 2 letras e 3 números (Ex: 000000000LA000).',
    'bi.size' => 'O BI deve ter exatamente 14 caracteres.',
    'ano_frequencia.in' => 'O ano de frequência deve ser obrigatoriamente o 4º ou 5º ano.'
]);

        DB::beginTransaction();

        $estudante = Estudante::findOrFail($id);

        $estudante->user->update([
            'name' => $request->name,
            'email' => $request->email,
                        'telefone' => $request->telefone,
        ]);

        $estudante->update([
            'curso_id' => $request->curso_id,
            'numero_matricula' => $request->numero_matricula,
            'regime' => $request->regime,
        ]);

        DB::commit();

        return redirect()
            ->route('estudantes.index')
            ->with('success','Estudante atualizado com sucesso');

    }catch(Exception $e){

        DB::rollBack();

        return back()->with('error',$e->getMessage());
    }
}
public function toggleAcesso($id)
{
    try {
        DB::beginTransaction();

        $estudante = Estudante::findOrFail($id);
        $user= $estudante->user;
        // Inverte o estado atual do acesso
        $novoEstado = !$user->acesso;
        $user->acesso = $novoEstado;
        $user->save(); // Salva o estado no estudante

        // Atualiza a flag correspondente no User caso necessário
      //  $user = $estudante->user;
        // Se no seu sistema o bloqueio de login também depende de uma coluna no User, descomente abaixo:
        // $user->active = $novoEstado; 
        $user->save();

        DB::commit();

        $mensagem = $novoEstado ? 'Acesso concedido com sucesso.' : 'Acesso retirado com sucesso.';
        return back()->with('success', $mensagem);

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Erro ao alterar acesso do estudante: ' . $e->getMessage());

        return back()->with('error', 'Erro ao alterar acesso do estudante: ' . $e->getMessage());
    }
}
 
    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $estudante = Estudante::findOrFail($id);

            // Ao apagar estudante, user será apagado por cascade
           
            $user=$estudante ->user;
            $user->acesso=false;
            $user->save();

            DB::commit();

            return back()->with('success','Acesso retirado  com sucesso');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Erro ao eliminar estudante: '.$e->getMessage());

            return back()->with('error','Erro ao eliminar estudante'.$e->getMessage());
        }
    }
         
}