@extends('app.private.dashboard.app.templete')

@section('title','Itens de Melhoria')

@section('content')
<div class="p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Itens de Melhoria</h1>
            <p class="text-sm text-gray-500 mt-1">
                Catálogo de sugestões para correção de anteprojetos
            </p>
        </div>

        <button onclick="openModal('createModal')"
            class="bg-gradient-to-r from-blue-600 to-indigo-600
                   text-white px-6 py-3 rounded-2xl font-semibold
                   shadow-lg hover:opacity-90 transition">
            + Novo Item
        </button>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase">Título</th>
                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase">Descrição</th>
                    <th class="px-8 py-4 text-right font-semibold text-gray-600 uppercase">Ações</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach($itens as $item)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-8 py-5 font-medium text-gray-800">{{ $item->titulo }}</td>
                    <td class="px-8 py-5 text-gray-600">{{ Str::limit($item->descricao, 80) }}</td>
                    <td class="px-8 py-5 text-right space-x-4">
                        <button onclick="openModal('editModal{{ $item->id }}')" class="text-blue-600 hover:text-blue-800">✏️</button>
                        <button onclick="openModal('deleteModal{{ $item->id }}')" class="text-red-600 hover:text-red-800">🗑️</button>
                    </td>
                </tr>

                <div id="editModal{{ $item->id }}" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-50">
                    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">
                        <h2 class="text-xl font-bold mb-6 text-gray-800">Editar Item</h2>
                        <form method="POST" action="{{ route('itensMelhoria.update', $item->id) }}">
                            @csrf @method('PUT')
                            <label class="block text-sm font-semibold mb-2">Título</label>
                            <input type="text" name="titulo" value="{{ $item->titulo }}" required class="w-full mb-4 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500">
                            
                            <label class="block text-sm font-semibold mb-2">Descrição</label>
                            <textarea name="descricao" rows="4" required class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500">{{ $item->descricao }}</textarea>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeModal('editModal{{ $item->id }}')" class="px-4 py-2 bg-gray-200 rounded-xl">Cancelar</button>
                                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700">Atualizar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="deleteModal{{ $item->id }}" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-50">
                    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">
                        <h2 class="text-xl font-bold mb-4 text-gray-800">Eliminar Item</h2>
                        <p class="text-gray-600 mb-6">Tem certeza que deseja eliminar <strong>{{ $item->titulo }}</strong>?</p>
                        <form method="POST" action="{{ route('itensMelhoria.destroy', $item->id) }}">
                            @csrf @method('DELETE')
                            <div class="flex justify-end gap-3">
                                <button type="button" onclick="closeModal('deleteModal{{ $item->id }}')" class="px-4 py-2 bg-gray-200 rounded-xl">Cancelar</button>
                                <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700">Eliminar</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $itens->links() }}
    </div>

    <div id="createModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-50">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Novo Item de Melhoria</h2>
            <form method="POST" action="{{ route('itensMelhoria.store') }}">
                @csrf
                <label class="block text-sm font-semibold mb-2">Título</label>
                <input type="text" name="titulo" required class="w-full mb-4 px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500">

                <label class="block text-sm font-semibold mb-2">Descrição</label>
                <textarea name="descricao" rows="4" required class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500"></textarea>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 bg-gray-200 rounded-xl">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">Criar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
.animate-fadeIn { animation: fadeIn .2s ease-out; }
</style>

<script>
function openModal(id){
    const modal = document.getElementById(id);
    modal.classList.replace('hidden', 'flex');
}
function closeModal(id){
    const modal = document.getElementById(id);
    modal.classList.replace('flex', 'hidden');
}
</script>
@endsection