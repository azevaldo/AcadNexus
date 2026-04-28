@extends('app.private.dashboard.app..templete')
@section('title','Estudantes')

@section('content')
<div class="p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Estudantes</h1>
            <p class="text-sm text-gray-500">Gestão de estudantes do sistema</p>
        </div>

        <a href="{{ route('estudantes.create') }}"
            class="bg-gradient-to-r from-blue-600 to-indigo-600
                   text-white px-6 py-3 rounded-xl font-semibold shadow">
            + Novo Estudante
        </a>
    </div>


    <!-- TABELA -->
    <div class="bg-white rounded-2xl shadow overflow-hidden border">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left">Nome</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-left">Curso</th>
                    <th class="px-6 py-4 text-left">Matrícula</th>
                    <th class="px-6 py-4 text-left">Regime</th>
                    <th class="px-6 py-4 text-right">Ações</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($estudantes as $estudante)

                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium">
                        {{ $estudante->user->name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $estudante->user->email }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $estudante->curso->nome }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $estudante->numero_matricula }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $estudante->regime }}
                    </td>

                    <td class="px-6 py-4 text-right space-x-3">

                        <!-- EDITAR -->
                        <a href="{{ route('estudantes.edit',$estudante->id) }}"
                           class="text-blue-600 hover:text-blue-800 text-lg">
                            ✏️
                        </a>

                        <!-- ELIMINAR -->
                        <button onclick="openModal('deleteModal{{ $estudante->id }}')"
                            class="text-red-600 hover:text-red-800 text-lg">
                            🗑️
                        </button>

                    </td>

                </tr>


                <!-- MODAL DELETE -->
                <div id="deleteModal{{ $estudante->id }}"
                     class="fixed inset-0 hidden items-center justify-center
                            bg-black/50 backdrop-blur-sm z-50">

                    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl">

                        <h2 class="text-xl font-bold mb-3">
                            Eliminar Estudante
                        </h2>

                        <p class="text-gray-600 mb-6">
                            Tem certeza que deseja eliminar
                            <strong>{{ $estudante->user->name }}</strong> ?
                        </p>

                        <form method="POST"
                            action="{{ route('estudantes.destroy',$estudante->id) }}">
                            @csrf
                            @method('DELETE')

                            <div class="flex justify-end gap-3">

                                <button type="button"
                                    onclick="closeModal('deleteModal{{ $estudante->id }}')"
                                    class="px-4 py-2 bg-gray-200 rounded-lg">
                                    Cancelar
                                </button>

                                <button type="submit"
                                    class="px-5 py-2 bg-red-600 text-white rounded-lg">
                                    Eliminar
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

                @endforeach

            </tbody>
        </table>

    </div>


    <div class="mt-6">
        {{ $estudantes->links() }}
    </div>

</div>


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