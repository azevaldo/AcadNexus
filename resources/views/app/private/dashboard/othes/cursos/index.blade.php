 @extends('app.private.dashboard.app..templete')

@section('title', 'Cursos - ' . $departamento->nome)

@section('content')
<div class="p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 uppercase tracking-tight">
                Cursos: {{ $departamento->nome }}
            </h1>
            <p class="text-gray-500 text-sm mt-1 font-medium">
                Lista de cursos vinculados a este departamento
            </p>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('departamentos.index') }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-2xl font-semibold transition duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-left text-xs"></i> Voltar
            </a>

            @hasanyrole('secretaria|admin')
            <button onclick="openModal('createCursoModal')"
                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90
                       text-white px-6 py-3 rounded-2xl font-semibold shadow-lg
                       transition duration-200">
                + Novo Curso
            </button>
            @endhasanyrole
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-8 py-4 font-semibold text-gray-600 uppercase tracking-wider w-20">ID</th>
                    <th class="px-8 py-4 font-semibold text-gray-600 uppercase tracking-wider">Nome do Curso</th>
                    <th class="px-8 py-4 text-right font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($cursos as $curso)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-8 py-5 font-medium text-gray-400 italic">
                        #{{ $curso->id }}
                    </td>

                    <td class="px-8 py-5 font-medium text-gray-800">
                        <div class="flex items-center gap-3">
                            <span class="text-amber-500"><i class="fas fa-graduation-cap"></i></span>
                            {{ $curso->nome }}
                        </div>
                    </td>

                    <td class="px-8 py-5 text-right space-x-4">
                    <button onclick="openModal('regimeModal{{ $curso->id }}')" 
       class="text-amber-600 hover:text-amber-800 transition" 
       title="Filtrar Estudantes por Regime">
        🎓
    </button>
    <a href="{{ route('cursos.campanhas', $curso->id) }}" 
       class="text-indigo-600 hover:text-indigo-800 transition" 
       title="Campanhas">
        📢
    </a>
                        @hasanyrole('secretaria|admin')
                        <button onclick="openModal('editCursoModal{{ $curso->id }}')"
                                class="text-blue-600 hover:text-blue-800 transition" 
                                title="Editar ">
                            ✏️
                        </button>

                        <button onclick="openModal('deleteCursoModal{{ $curso->id }}')"
                                class="text-red-600 hover:text-red-800 transition" 
                                title="Eliminar">
                            🗑️
                        </button>
                        @endhasanyrole
                    </td>
                </tr>
{{-- MODAL DE SELEÇÃO DE REGIME --}}
<div id="regimeModal{{ $curso->id }}"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8 animate-fadeIn text-left">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-3 bg-amber-100 text-amber-600 rounded-2xl">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Ver Estudantes</h2>
                <p class="text-xs text-gray-500">{{ $curso->nome }}</p>
            </div>
        </div>
{{-- Dentro do seu regimeModal{{ $curso->id }} --}}
<form method="GET" action="{{ route('cursos.estudantes', $curso->id) }}">
    <div class="space-y-4">
        {{-- Filtro de Regime --}}
        <div>
            <label class="block text-sm font-semibold mb-2 text-gray-600">Regime</label>
            <select name="regime" class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-amber-500 focus:outline-none bg-gray-50 text-sm">
                <option value="">Todos os Regimes</option>
                <option value="Regular">Regular</option>
                <option value="Pós Laboral">Pós Laboral</option>
            </select>
        </div>

        {{-- Novo Filtro de Ano de Frequência (Opcional) --}}
        <div>
            <label class="block text-sm font-semibold mb-2 text-gray-600">Ano de Frequência</label>
            <select name="ano" class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-amber-500 focus:outline-none bg-gray-50 text-sm">
                <option value="">Todos os Anos</option>
                @for ($i = 1; $i <= 5; $i++) {{-- Ajuste o limite de anos conforme necessário --}}
                    <option value="{{ $i }}">{{ $i }}º Ano</option>
                @endfor
            </select>
        </div>
    </div>

    <div class="flex justify-end gap-3 mt-8">
        <button type="button" onclick="closeModal('regimeModal{{ $curso->id }}')"
                class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium">
            Cancelar
        </button>
        <button type="submit" class="px-6 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600 shadow-lg font-bold">
            Filtrar
        </button>
    </div>
</form>    </div>
</div>
                <div id="editCursoModal{{ $curso->id }}"
                     class="fixed inset-0 bg-black/50 backdrop-blur-sm
                            hidden items-center justify-center z-50">
                    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">
                        <h2 class="text-xl font-bold mb-6 text-gray-800">Editar Curso</h2>
                        
                        <form method="POST" action="{{ route('cursos.update', $curso->id) }}">
                            @csrf
                            @method('PUT')
                            <label class="block text-sm font-semibold mb-2 text-gray-600">Nome do Curso</label>
                            <input type="text" name="nome" value="{{ $curso->nome }}" required
                                   class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            
                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" onclick="closeModal('editCursoModal{{ $curso->id }}')"
                                        class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">Cancelar</button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="deleteCursoModal{{ $curso->id }}"
                     class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
                    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">
                        <h2 class="text-xl font-bold mb-4 text-gray-800">Confirmar Exclusão</h2>
                        <p class="text-gray-600 mb-6">Deseja eliminar o curso <strong>{{ $curso->nome }}</strong>?</p>
                        <form method="POST" action="{{ route('cursos.destroy', $curso->id) }}">
                            @csrf @method('DELETE')
                            <div class="flex justify-end gap-3">
                                <button type="button" onclick="closeModal('deleteCursoModal{{ $curso->id }}')"
                                        class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">Cancelar</button>
                                <button type="submit" class="px-5 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700 shadow">Eliminar</button>
                            </div>
                        </form>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="3" class="px-8 py-10 text-center text-gray-500 italic">
                        Nenhum curso cadastrado neste departamento.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="createCursoModal"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Novo Curso</h2>
            <form method="POST" action="{{ route('cursos.store') }}">
                @csrf
                <input type="hidden" name="departamento_id" value="{{ $departamento->id }}">
                
                <label class="block text-sm font-semibold mb-2 text-gray-600">Nome do Curso</label>
                <input type="text" name="nome" required
                       class="w-full px-4 py-2 rounded-xl border focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Ex: Engenharia Informática">

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('createCursoModal')"
                            class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 shadow">Criar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn { animation: fadeIn 0.2s ease-out; }
</style>

<script>
function openModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection