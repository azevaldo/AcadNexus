<?php



namespace App\Http\Controllers;

use App\Models\ItemMelhoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemMelhoriaController extends Controller
{
    public function index()
    {
        try {
            $itens = ItemMelhoria::paginate(10);
            return view('app.private.dashboard.othes.itensMelhoria.index', compact('itens'));
        } catch (Exception $e) {
            Log::error("Erro itens melhoria index: ".$e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Erro ao carregar itens de melhoria');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|min:3|max:150|unique:itens_melhoria,titulo',
            'descricao' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();
            ItemMelhoria::create([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao
            ]);
            DB::commit();

            return redirect()->route('itensMelhoria.index')
                ->with('success', 'Item de melhoria criado com sucesso');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erro ao criar item de melhoria: ".$e->getMessage());
            return redirect()->back()->with('error', 'Erro ao criar item.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => "required|string|min:3|max:150|unique:itens_melhoria,titulo,$id",
            'descricao' => 'required|string|max:500'
        ]);

        try {
            DB::beginTransaction();
            $item = ItemMelhoria::findOrFail($id);
            $item->update([
                'titulo' => $request->titulo,
                'descricao' => $request->descricao
            ]);
            DB::commit();

            return redirect()->route('itensMelhoria.index')
                ->with('success', 'Item atualizado com sucesso');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Erro ao atualizar item: ".$e->getMessage());
            return redirect()->back()->with('error', 'Erro ao atualizar item.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            ItemMelhoria::findOrFail($id)->delete();
            DB::commit();
            return redirect()->route('itensMelhoria.index')
                ->with('success', 'Item eliminado com sucesso');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Não foi possível eliminar o item.');
        }
    }
}