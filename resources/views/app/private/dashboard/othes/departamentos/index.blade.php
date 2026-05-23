 @extends('app.private.dashboard.app..templete')

@section('title','Departamentos')

@section('content')
<div class="p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Departamentos</h1>
            <p class="text-gray-500 text-sm mt-1">
                Gestão completa dos departamentos cadastrados
            </p>
        </div>
 @hasanyrole('secretaria|admin')
        <button onclick="openModal('createModal')"
            class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90
                   text-white px-6 py-3 rounded-2xl font-semibold shadow-lg
                   transition duration-200">
            + Novo Departamento
        </button>

        @endhasanyrole
    </div>

    <!-- CARD TABELA -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider">
                        Nome
                    </th>
                    <th class="px-8 py-4 text-right font-semibold text-gray-600 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach($departamentos as $departamento)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-8 py-5 font-medium text-gray-800">
                        {{ $departamento->nome }}
                    </td>

                   <td class="px-8 py-5 text-right space-x-4">
    <a href="{{ route('departamento.cursos', $departamento->id) }}" 
   class="text-amber-600 hover:text-amber-800 transition" 
   title="Cursos">
   <i class="fas fa-graduation-cap"></i>
</a>
                   <a href="{{ route('departamentos.professores', $departamento->id) }}" 
       class="text-amber-600 hover:text-amber-800 transition" 
       title="Listar Professores deste Departamento">
        👥
    </a>
                         @hasanyrole('secretaria|admin')
            <!-- EDIT -->
             <a href="" onclick="openModal('editModal{{ $departamento->id }}')"
                            class="text-blue-600 hover:text-blue-800 transition" title="Editar">✏️</a>
   

                       <a href="" onclick="openModal('deleteModal{{ $departamento->id }}')"
                            class="text-red-600 hover:text-red-800 transition" title="Eliminar">🗑️</a>
                        <!-- DELETE -->
 
            
                         @endhasanyrole
                        
                       
                    </td>
                </tr>

                <!-- MODAL EDIT -->
                <div id="editModal{{ $departamento->id }}"
                     class="fixed inset-0 bg-black/50 backdrop-blur-sm
                            hidden items-center justify-center z-50">

                    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">

                        <h2 class="text-xl font-bold mb-6 text-gray-800">
                            Editar Departamento
                        </h2>

                        <form method="POST"
                              action="{{ route('departamentos.update',$departamento->id) }}">
                            @csrf
                            @method('PUT')

                            <label class="block text-sm font-semibold mb-2 text-gray-600">
                                Nome
                            </label>

                            <input type="text"
                                   name="nome"
                                   value="{{ $departamento->nome }}"
                                   required
                                   class="w-full px-4 py-3 rounded-xl border
                                          focus:ring-2 focus:ring-blue-500
                                          focus:outline-none">

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button"
                                    onclick="closeModal('editModal{{ $departamento->id }}')"
                                    class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">
                                    Cancelar
                                </button>

                                <button type="submit"
                                    class="px-5 py-2 rounded-xl bg-blue-600
                                           text-white hover:bg-blue-700 shadow">
                                    Salvar
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- MODAL DELETE -->
                <div id="deleteModal{{ $departamento->id }}"
                     class="fixed inset-0 bg-black/50 backdrop-blur-sm
                            hidden items-center justify-center z-50">

                    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">

                        <h2 class="text-xl font-bold mb-4 text-gray-800">
                            Confirmar Exclusão
                        </h2>

                        <p class="text-gray-600 mb-6">
                            Deseja realmente eliminar
                            <strong>{{ $departamento->nome }}</strong>?
                        </p>

                        <form method="POST"
                              action="{{ route('departamentos.destroy',$departamento->id) }}">
                            @csrf
                            @method('DELETE')

                            <div class="flex justify-end gap-3">
                                <button type="button"
                                    onclick="closeModal('deleteModal{{ $departamento->id }}')"
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
        {{ $departamentos->links() }}
    </div>


    <!-- MODAL CREATE -->
    <div id="createModal"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm
                hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-8 animate-fadeIn">

            <h2 class="text-xl font-bold mb-6 text-gray-800">
                Novo Departamento
            </h2>

            <form method="POST" action="{{ route('departamentos.store') }}">
                @csrf

                <label class="block text-sm font-semibold mb-2 text-gray-600">
                    Nome
                </label>

                <input type="text"
                       name="nome"
                       required
                       class="w-full px-4 py-3 rounded-xl border
                              focus:ring-2 focus:ring-blue-500
                              focus:outline-none">

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