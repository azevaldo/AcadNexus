@extends('app.private.dashboard.app.templete')

@section('title','Itens de Melhoria')

@section('content')
<div class="p-8">

    {{-- CABEÇALHO DA TELA --}}
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

    {{-- TABELA DE ITENS --}}
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
                    <td class="px-8 py-5 font-medium text-gray-800 item-titulo">{{ $item->titulo }}</td>
                    
                    {{-- DESCRIÇÃO LIMITADA INTEGRADA COM TAILWIND --}}
                    <td class="px-8 py-5 text-gray-600 max-w-xs md:max-w-md">
                        <div class="flex items-center gap-2">
                            {{-- Texto truncado via Tailwind --}}
                            <span class="truncate block max-w-[280px]">
                                {{ Str::limit($item->descricao, 60, '...') }}
                            </span>
                            
                            {{-- Texto completo escondido em uma tag invisível --}}
                            <div class="hidden text-completo-original">{{ $item->descricao }}</div>

                            @if(strlen($item->descricao) > 60)
                                <button type="button" 
                                        onclick="dispararShowModal(this)" 
                                        class="text-blue-500 hover:text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md text-xs font-bold transition flex items-center gap-1 shrink-0">
                                    <i class="fas fa-eye text-[10px]"></i> Ver mais
                                </button>
                            @endif
                        </div>
                    </td>

                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <button onclick="openModal('editModal{{ $item->id }}')"
                                class="w-9 h-9 flex items-center justify-center rounded-lg
                                       text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition"
                                title="Editar">
                                <i class="fas fa-pen-to-square"></i>
                            </button>

                            <button onclick="openModal('deleteModal{{ $item->id }}')"
                                class="w-9 h-9 flex items-center justify-center rounded-lg
                                       text-slate-500 hover:bg-red-50 hover:text-red-600 transition"
                                title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- MODAL: EDITAR (Usando Utilitários nativos do Tailwind) --}}
                <div id="editModal{{ $item->id }}" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-50 p-4">
                    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">
                        <h2 class="text-xl font-bold mb-6 text-gray-800">Editar Item</h2>
                        <form method="POST" action="{{ route('itensMelhoria.update', $item->id) }}">
                            @csrf @method('PUT')
                            
                            <label class="block text-sm font-semibold mb-2">Título</label>
                            <input type="text" name="titulo" value="{{ $item->titulo }}" required minlength="3" maxlength="100"
                                   pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$"
                                   class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <p class="text-xs text-gray-500 mt-2 mb-4">
                                Mínimo de 3 caracteres. Apenas letras, números e espaços.
                            </p>

                            <label class="block text-sm font-semibold mb-2">Descrição</label>
                            <textarea name="descricao" rows="4" required class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500">{{ $item->descricao }}</textarea>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeModal('editModal{{ $item->id }}')" class="px-4 py-2 bg-gray-200 rounded-xl">Cancelar</button>
                                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700">Atualizar</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- MODAL: ELIMINAR --}}
                <div id="deleteModal{{ $item->id }}" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-50 p-4">
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

    {{-- PAGINAÇÃO --}}
    <div class="mt-8">
        {{ $itens->links() }}
    </div>

    {{-- MODAL: CRIAR NOVO ITEM --}}
    <div id="createModal" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Novo Item de Melhoria</h2>
            <form method="POST" action="{{ route('itensMelhoria.store') }}" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                @csrf
                <label class="block text-sm font-semibold mb-2">Título</label>
                <input type="text" name="titulo" required minlength="3" maxlength="100"
                       pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$"
                       class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <p class="text-xs text-gray-500 mt-2 mb-4">
                    Mínimo de 3 caracteres. Apenas letras, números e espaços.
                </p>

                <label class="block text-sm font-semibold mb-2">Descrição</label>
                <textarea name="descricao" rows="4" required class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500"></textarea>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 bg-gray-200 rounded-xl">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">Criar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ESTILIZADO EM TAILWIND PARA EXIBIR A DESCRIÇÃO COMPLETA --}}
    <div id="showDescModal" class="fixed inset-0 hidden items-center justify-center bg-black/60 backdrop-blur-sm z-50 p-4">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-fadeIn">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest block">Título do Item</span>
                    <h3 id="showModalTitle" class="font-bold text-base mt-0.5 text-white">---</h3>
                </div>
                <button type="button" onclick="closeModal('showDescModal')" class="text-white/60 hover:text-white p-1 transition">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-8 max-h-[60vh] overflow-y-auto">
                <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest block mb-2">Descrição Completa</span>
                <p id="showModalContent" class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">---</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="button" onclick="closeModal('showDescModal')" class="px-5 py-2 bg-slate-800 text-white rounded-xl font-bold text-xs uppercase transition hover:bg-slate-700">
                    Fechar
                </button>
            </div>
        </div>
    </div>

</div>

{{-- CLASSES DE ANIMAÇÃO --}}
<style>
@keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
.animate-fadeIn { animation: fadeIn .2s ease-out; }
</style>

{{-- CORREÇÃO DO SCRIPT COM CLASSES DO TAILWIND --}}
<script>
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}

// Coleta as strings com segurança direto da árvore do HTML e abre o modal descritivo
function dispararShowModal(buttonElement) {
    const row = buttonElement.closest('tr');
    
    // Captura os dados internos da linha correspondente
    const titulo = row.querySelector('.item-titulo').innerText;
    const descricaoCompleta = row.querySelector('.text-completo-original').innerText;
    
    // Injeta os dados limpos nos elementos do modal
    document.getElementById('showModalTitle').innerText = titulo;
    document.getElementById('showModalContent').innerText = descricaoCompleta;
    
    // Chama abertura usando a classe flex do Tailwind
    openModal('showDescModal');
}
</script>
@endsection