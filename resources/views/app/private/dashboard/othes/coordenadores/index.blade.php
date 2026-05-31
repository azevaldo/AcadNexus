 @extends('app.private.dashboard.app.templete')

@section('title','Coordenadores')

@section('content')

<div class="p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Coordenadores
            </h1>

            <p class="text-gray-500 text-sm mt-1">
                Gestão dos coordenadores de departamento
            </p>
        </div>

        <button onclick="openModal('createModal')"
            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90
                   text-white px-6 py-3 rounded-2xl font-semibold shadow-lg
                   transition duration-200">

            + Novo Coordenador

        </button>

    </div>


    <!-- CARD TABELA -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 border-b">

                <tr>

                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider">
                        Departamento
                    </th>

                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider">
                        Professor
                    </th>

                    <th class="px-8 py-4 text-right font-semibold text-gray-600 uppercase tracking-wider">
                        Ações
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100">

                @foreach($coordenadores as $coordenador)

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-8 py-5 font-medium text-gray-800">
                        {{ $coordenador->departamento->nome }}
                    </td>

                    <td class="px-8 py-5 text-gray-700">
                        {{ $coordenador->professor->user->name }}
                    </td>

                    <td class="px-8 py-5 text-right">

    <button onclick="openModal('deleteModal{{ $coordenador->id }}')"
        class="text-slate-500 hover:text-red-600 transition duration-200"
        title="Remover Coordenador">

        <i class="fas fa-trash"></i>

    </button>

</td>

                </tr>


                <!-- MODAL DELETE -->
                <div id="deleteModal{{ $coordenador->id }}"
                     class="fixed inset-0 bg-black/50 backdrop-blur-sm
                            hidden items-center justify-center z-50">

                    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">

                        <h2 class="text-xl font-bold mb-4 text-gray-800">
                            Confirmar Exclusão
                        </h2>

                        <p class="text-gray-600 mb-6">
                            Deseja realmente remover este coordenador?
                        </p>

                        <form method="POST"
                              action="{{ route('coordenadores.destroy',$coordenador) }}">

                            @csrf
                            @method('DELETE')

                            <div class="flex justify-end gap-3">

                                <button type="button"
                                    onclick="closeModal('deleteModal{{ $coordenador->id }}')"
                                    class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">

                                    Cancelar

                                </button>

                                <button type="submit"
                                    class="px-5 py-2 rounded-xl bg-red-600
                                           text-white hover:bg-red-700 shadow">

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


    <!-- PAGINAÇÃO -->
    <div class="mt-8">
        {{ $coordenadores->links() }}
    </div>



    <!-- MODAL CREATE -->
    <div id="createModal"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm
                hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">

            <h2 class="text-xl font-bold mb-6 text-gray-800">
                Novo Coordenador
            </h2>

            <form method="POST" action="{{ route('coordenadores.store') }}">

                @csrf

                <label class="block text-sm font-semibold mb-2 text-gray-600">
                    Departamento
                </label>

                <select name="departamento_id"
                        class="w-full px-4 py-3 rounded-xl border
                               focus:ring-2 focus:ring-blue-500
                               focus:outline-none"
                        required>

                    @foreach($departamentos as $dep)

                        <option value="{{ $dep->id }}">
                            {{ $dep->nome }}
                        </option>

                    @endforeach

                </select>


                <label class="block text-sm font-semibold mt-4 mb-2 text-gray-600">
                    Professor
                </label>

                <select name="professor_id"
                        class="w-full px-4 py-3 rounded-xl border
                               focus:ring-2 focus:ring-blue-500
                               focus:outline-none"
                        required>

                    @foreach($professores as $prof)

                        <option value="{{ $prof->id }}">
                            {{ $prof->user->name }}
                        </option>

                    @endforeach

                </select>


                <div class="flex justify-end gap-3 mt-6">

                    <button type="button"
                        onclick="closeModal('createModal')"
                        class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">

                        Cancelar

                    </button>

                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-blue-600
                               text-white hover:bg-blue-700 shadow">

                        Criar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- ANIMAÇÃO -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn {
    animation: fadeIn 0.2s ease-out;
}
</style>


<!-- JS MODAIS -->
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