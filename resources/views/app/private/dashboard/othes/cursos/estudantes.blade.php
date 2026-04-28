@extends('app.private.dashboard.app..templete')

@section('title','Estudantes')

@section('content')
<div class="p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                @if(isset($curso))
                    Estudantes: <span class="text-blue-600">{{ $curso->nome }}</span>
                @else
                    Estudantes
                @endif
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                @if(isset($curso))
                    Lista de alunos matriculados no curso de <strong>{{ $curso->nome }}</strong>.
                @else
                    Gestão de estudantes do sistema
                @endif
            </p>
        </div>

        <div class="flex gap-3">
            @if(isset($curso))
                <a href="{{ route('cursos.index') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold transition flex items-center border">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar para Cursos
                </a>
            @endif

            <a href="{{ route('cursos.estudantesCreate',$curso->id) }}"
                class="bg-gradient-to-r from-blue-600 to-indigo-600
                       text-white px-6 py-3 rounded-xl font-semibold shadow transition hover:opacity-90">
                + Novo Estudante
            </a>
        </div>
    </div>


    <div class="bg-white rounded-2xl shadow overflow-hidden border border-gray-100">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 border-b">
                <tr class="text-gray-600 font-semibold uppercase tracking-wider">
                    <th class="px-6 py-4 text-left">Nome</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-left">Curso</th>
                    <th class="px-6 py-4 text-left">Matrícula</th>
                    <th class="px-6 py-4 text-left">Regime</th>
                    <th class="px-6 py-4 text-right">Ações</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($estudantes as $estudante)

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $estudante->user->name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $estudante->user->email }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-xs font-bold">
                            {{ $estudante->curso->nome }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-gray-600 font-mono">
                        {{ $estudante->numero_matricula }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="text-gray-600">{{ $estudante->regime }}</span>
                    </td>

                    <td class="px-6 py-4 text-right space-x-3">

                        <a href="{{ route('estudantes.edit',$estudante->id) }}"
                           class="text-blue-600 hover:text-blue-800 transition" title="Editar">
                            ✏️
                        </a>

                        <button onclick="openModal('deleteModal{{ $estudante->id }}')"
                            class="text-red-600 hover:text-red-800 transition" title="Excluir">
                            🗑️
                        </button>

                    </td>
                </tr>

                <div id="deleteModal{{ $estudante->id }}"
                     class="fixed inset-0 hidden items-center justify-center
                            bg-black/50 backdrop-blur-sm z-50">

                    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl animate-fadeIn">
                        <h2 class="text-xl font-bold mb-3">Eliminar Estudante</h2>
                        <p class="text-gray-600 mb-6">
                            Tem certeza que deseja eliminar 
                            <strong>{{ $estudante->user->name }}</strong>?
                        </p>
                        <form method="POST" action="{{ route('estudantes.destroy',$estudante->id) }}">
                            @csrf
                            @method('DELETE')
                            <div class="flex justify-end gap-3">
                                <button type="button" onclick="closeModal('deleteModal{{ $estudante->id }}')"
                                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                                    Cancelar
                                </button>
                                <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                    Eliminar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                        Nenhum estudante encontrado para este curso.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

    </div>

    <div class="mt-6">
        {{ $estudantes->links() }}
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
function openModal(id){
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeModal(id){
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection