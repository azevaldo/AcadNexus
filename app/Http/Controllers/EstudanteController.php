<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Estudante;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class EstudanteController extends Controller
{
    public function index()
    {
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

    public function create()
    {
        $cursos = Curso::orderBy('nome')->get();

        return view('app.private.dashboard.othes.estudantes.create',
            compact('cursos'));
    }
    public function edit($id)
{
    $estudante = Estudante::with('user')->findOrFail($id);
    $cursos = Curso::all();
$tutores = Tutor::with('professor.user')->get();
    return view(
        'app.private.dashboard.othes.estudantes.edit',
        compact('estudante','cursos','tutores')
    );
}

    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|min:3|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',

                'curso_id' => 'required|exists:cursos,id',
                'numero_matricula' => 'required|unique:estudantes,numero_matricula',
                'regime' => 'required|in:Regular,Pós Laboral'
            ]);

            DB::beginTransaction();

            // 🔹 1️⃣ Criar usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // 🔹 2️⃣ Criar estudante vinculado
            
            Estudante::create([
                'user_id' => $user->id,
                'curso_id' => $request->curso_id,
                'numero_matricula' => $request->numero_matricula,
                'regime' => $request->regime
            ]);
$user-> assignRole('estudante');
            DB::commit();

            return redirect()->route('estudantes.index')
                ->with('success','Estudante criado com sucesso');

           } catch(Exception $e){
       
           // dd($e->getMessage());
            DB::rollBack();
            Log::error('Erro ao criar estudante: '.$e->getMessage());

            return back()->with('error','Erro ao criar estudante'.$e->getMessage())
                         ->withInput();
        }
    }

    public function update(Request $request, $id)
{
    try{

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'curso_id' => 'required',
            'numero_matricula' => 'required',
        ]);

        DB::beginTransaction();

        $estudante = Estudante::findOrFail($id);

        $estudante->user->update([
            'name' => $request->name,
            'email' => $request->email,
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
    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $estudante = Estudante::findOrFail($id);

            // Ao apagar estudante, user será apagado por cascade
            $estudante->delete();

            DB::commit();

            return back()->with('success','Estudante eliminado com sucesso');

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Erro ao eliminar estudante: '.$e->getMessage());

            return back()->with('error','Erro ao eliminar estudante'.$e->getMessage());
        }
    }
}