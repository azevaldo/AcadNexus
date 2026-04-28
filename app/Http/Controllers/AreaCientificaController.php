<?php

namespace App\Http\Controllers;

use App\Models\AreaCientifica;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AreaCientificaController extends Controller
{
    public function __construct()
    {
        // $this->middleware('roles:admin')->only(['destroy']);
    }

    public function index()
    {
        try {

            $areas = AreaCientifica::paginate(10);

            return view(
                'app.private.dashboard.othes.areas.index',
                compact('areas')
            );

        } catch (Exception $e) {

            Log::error("Erro areas cientificas index: ".$e->getMessage());

            return redirect()
                ->route('dashboard')
                ->with('error', 'Erro ao carregar áreas científicas'.$e->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {

            $request->validate([
                'nome' => [
                    'required',
                    'string',
                    'min:3',
                    'max:150',
                    'regex:/^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$/u',
                    'unique:areas_cientificas,nome'
                ],
                'descricao' => [
                    'nullable',
                    'string',
                    'max:500'
                ]
            ]);

            DB::beginTransaction();

            AreaCientifica::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao
            ]);

            DB::commit();

            return redirect()
                ->route('areasCientificas.index')
                ->with('success', 'Área científica criada com sucesso');

        } catch (Exception $e) {

            DB::rollBack();

            Log::error("Erro ao criar área científica: ".$e->getMessage());
    dd($e->getMessage());
            return redirect()
                ->route('areasCientificas.index')
                ->with('error', 'Erro ao criar área científica'.$e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'nome' => [
                    'required',
                    'string',
                    'min:3',
                    'max:150',
                    'regex:/^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$/u',
                    "unique:areas_cientificas,nome,$id"
                ],
                'descricao' => [
                    'nullable',
                    'string',
                    'max:500'
                ]
            ]);

            DB::beginTransaction();

            $area = AreaCientifica::findOrFail($id);

            $area->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao
            ]);

            DB::commit();

            return redirect()
                ->route('areasCientificas.index')
                ->with('success', 'Área científica atualizada com sucesso');

        } catch (Exception $e) {

            DB::rollBack();

            Log::error("Erro ao atualizar área científica: ".$e->getMessage());

            return redirect()
                ->route('areasCientificas.index')
                ->with('error', 'Erro ao atualizar área científica'.$e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            AreaCientifica::findOrFail($id)->delete();

            DB::commit();

            return redirect()
                ->route('areasCientificas.index')
                ->with('success', 'Área científica eliminada');

        } catch (Exception $e) {

            DB::rollBack();

            Log::error("Erro ao eliminar área científica: ".$e->getMessage());

            return redirect()
                ->route('areasCientificas.index')
                ->with('error', 'Erro ao eliminar área científica'.$e->getMessage());
        }
    }
}