 @extends('app.private.dashboard.app..templete')

@section('title','Cursos')

@section('content')
<div class="p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Cursos</h1>
            <p class="text-sm text-gray-500 mt-1">
                Gestão dos cursos por departamento
            </p>
        </div>

        <button onclick="openModal('createModal')"
            class="bg-gradient-to-r from-blue-600 to-indigo-600
                   text-white px-6 py-3 rounded-2xl font-semibold
                   shadow-lg hover:opacity-90 transition">
            + Novo Curso
        </button>
    </div>

    <!-- TABELA -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase">
                        Curso
                    </th>
                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase">
                        Departamento
                    </th>
                    <th class="px-8 py-4 text-right font-semibold text-gray-600 uppercase">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach($cursos as $curso)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-8 py-5 font-medium text-gray-800">
                        {{ $curso->nome }}
                    </td>

                    <td class="px-8 py-5 text-gray-700">
                        {{ $curso->departamento->nome }}
                    </td>

                    {{-- Na View de Cursos, dentro do @foreach($cursos as $curso) --}}
<td class="px-8 py-5 text-right space-x-4">

    <a href="{{ route('cursos.estudantes', $curso->id) }}" 
       class="text-amber-600 hover:text-amber-800 transition" 
       title="Listar Estudantes deste Curso">
        🎓
    </a>
    <a href="{{ route('cursos.campanhas', $curso->id) }}" 
       class="text-indigo-600 hover:text-indigo-800 transition" 
       title="Ver Campanhas deste curso">
        📢
    </a>

    <button onclick="openModal('editModal{{ $curso->id }}')" class="text-blue-600 hover:text-blue-800">
        ✏️
    </button>

    <button onclick="openModal('deleteModal{{ $curso->id }}')" class="text-red-600 hover:text-red-800">
        🗑️
    </button>
</td>
                </tr>

                <!-- MODAL EDIT -->
                <div id="editModal{{ $curso->id }}"
                     class="fixed inset-0 hidden items-center justify-center
                            bg-black/50 backdrop-blur-sm z-50">

                    <div class="bg-white w-full max-w-md rounded-3xl
                                shadow-2xl p-8 animate-fadeIn">

                        <h2 class="text-xl font-bold mb-6 text-gray-800">
                            Editar Curso
                        </h2>

                        <form method="POST"
                              action="{{ route('cursos.update',$curso->id) }}">
                            @csrf
                            @method('PUT')

                            <label class="block text-sm font-semibold mb-2">
                                Departamento
                            </label>
                            <select name="departamento_id" required
                                class="w-full mb-4 px-4 py-3 rounded-xl border
                                       focus:ring-2 focus:ring-blue-500">
                                @foreach($departamentos as $dep)
                                    <option value="{{ $dep->id }}"
                                        @selected($dep->id == $curso->departamento_id)>
                                        {{ $dep->nome }}
                                    </option>
                                @endforeach
                            </select>

                            <label class="block text-sm font-semibold mb-2">
                                Nome do Curso
                            </label>
                            <input type="text" name="nome"
                                   value="{{ $curso->nome }}" required
                                   class="w-full px-4 py-3 rounded-xl border
                                          focus:ring-2 focus:ring-blue-500">

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button"
                                    onclick="closeModal('editModal{{ $curso->id }}')"
                                    class="px-4 py-2 bg-gray-200 rounded-xl">
                                    Cancelar
                                </button>

                                <button type="submit"
                                    class="px-5 py-2 bg-blue-600 text-white
                                           rounded-xl shadow hover:bg-blue-700">
                                    Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- MODAL DELETE -->
                <div id="deleteModal{{ $curso->id }}"
                     class="fixed inset-0 hidden items-center justify-center
                            bg-black/50 backdrop-blur-sm z-50">

                    <div class="bg-white w-full max-w-md rounded-3xl
                                shadow-2xl p-8 animate-fadeIn">

                        <h2 class="text-xl font-bold mb-4 text-gray-800">
                            Eliminar Curso
                        </h2>

                        <p class="text-gray-600 mb-6">
                            Tem certeza que deseja eliminar o curso
                            <strong>{{ $curso->nome }}</strong>?
                        </p>

                        <form method="POST"
                              action="{{ route('cursos.destroy',$curso->id) }}">
                            @csrf
                            @method('DELETE')

                            <div class="flex justify-end gap-3">
                                <button type="button"
                                    onclick="closeModal('deleteModal{{ $curso->id }}')"
                                    class="px-4 py-2 bg-gray-200 rounded-xl">
                                    Cancelar
                                </button>

                                <button type="submit"
                                    class="px-5 py-2 bg-red-600 text-white
                                           rounded-xl hover:bg-red-700">
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
        {{ $cursos->links() }}
    </div>

    <!-- MODAL CREATE -->
    <div id="createModal"
         class="fixed inset-0 hidden items-center justify-center
                bg-black/50 backdrop-blur-sm z-50">

        <div class="bg-white w-full max-w-md rounded-3xl
                    shadow-2xl p-8 animate-fadeIn">

            <h2 class="text-xl font-bold mb-6 text-gray-800">
                Novo Curso
            </h2>

            <form method="POST" action="{{ route('cursos.store') }}">
                @csrf

                <label class="block text-sm font-semibold mb-2">
                    Departamento
                </label>
                <select name="departamento_id" required
                    class="w-full mb-4 px-4 py-3 rounded-xl border
                           focus:ring-2 focus:ring-blue-500">
                    @foreach($departamentos as $dep)
                        <option value="{{ $dep->id }}">{{ $dep->nome }}</option>
                    @endforeach
                </select>

                <label class="block text-sm font-semibold mb-2">
                    Nome do Curso
                </label>
                <input type="text" name="nome" required
                       class="w-full px-4 py-3 rounded-xl border
                              focus:ring-2 focus:ring-blue-500">

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button"
                        onclick="closeModal('createModal')"
                        class="px-4 py-2 bg-gray-200 rounded-xl">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white
                               rounded-xl hover:bg-blue-700">
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
    animation: fadeIn .2s ease-out;
}
</style>

<!-- JS MODAIS -->
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