@extends('app.private.dashboard.app..templete')

@section('title','Áreas Científicas')

@section('content')
<div class="p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Áreas Científicas</h1>
            <p class="text-sm text-gray-500 mt-1">
                Gestão das áreas científicas da instituição
            </p>
        </div>

        <button onclick="openModal('createModal')"
            class="bg-gradient-to-r from-blue-600 to-indigo-600
                   text-white px-6 py-3 rounded-2xl font-semibold
                   shadow-lg hover:opacity-90 transition">
            + Nova Área
        </button>
    </div>

    <!-- TABELA -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase">
                        Nome
                    </th>
                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase">
                        Descrição
                    </th>
                    <th class="px-8 py-4 text-right font-semibold text-gray-600 uppercase">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @foreach($areas as $area)

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-8 py-5 font-medium text-gray-800">
                        {{ $area->nome }}
                    </td>

                    <td class="px-8 py-5 text-gray-600">
                        {{ $area->descricao ?? '-' }}
                    </td>

                   <td class="px-8 py-5 text-right">

    <div class="flex justify-end items-center gap-2">

        <!-- EDITAR -->
        <button onclick="openModal('editModal{{ $area->id }}')"
            class="w-9 h-9 flex items-center justify-center rounded-lg
                   text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition"
            title="Editar Área">

            <i class="fas fa-pen-to-square"></i>

        </button>

        <!-- ELIMINAR -->
        <button onclick="openModal('deleteModal{{ $area->id }}')"
            class="w-9 h-9 flex items-center justify-center rounded-lg
                   text-slate-500 hover:bg-red-50 hover:text-red-600 transition"
            title="Eliminar Área">

            <i class="fas fa-trash"></i>

        </button>

    </div>

</td>
                </tr>

                <!-- MODAL EDIT -->
                <div id="editModal{{ $area->id }}"
                     class="fixed inset-0 hidden items-center justify-center
                            bg-black/50 backdrop-blur-sm z-50">

                    <div class="bg-white w-full max-w-md rounded-3xl
                                shadow-2xl p-8 animate-fadeIn">

                        <h2 class="text-xl font-bold mb-6 text-gray-800">
                            Editar Área Científica
                        </h2>

                        <form method="POST"
                              action="{{ route('areasCientificas.update',$area->id) }}" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                            @csrf
                            @method('PUT')

                            <label class="block text-sm font-semibold mb-2">
                                Nome
                            </label>

                            

                                          <input type="text"
       name="nome"
       value="{{ $area->nome }}"
       required
       minlength="3"
       maxlength="100"
       pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$"
       title="O nome deve possuir entre 3 e 100 caracteres e conter apenas letras, números e espaços."
       class="w-full px-4 py-3 rounded-xl border
              focus:ring-2 focus:ring-blue-500
              focus:outline-none">

<p class="text-xs text-gray-500 mt-2">
    Mínimo de 3 caracteres. Apenas letras, números e espaços.
</p>
                            <label class="block text-sm font-semibold mb-2">
                                Descrição
                            </label>

                            <textarea name="descricao"
                                      class="w-full px-4 py-3 rounded-xl border
                                             focus:ring-2 focus:ring-blue-500">{{ $area->descricao }}</textarea>

                            <div class="flex justify-end gap-3 mt-6">

                                <button type="button"
                                    onclick="closeModal('editModal{{ $area->id }}')"
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
                <div id="deleteModal{{ $area->id }}"
                     class="fixed inset-0 hidden items-center justify-center
                            bg-black/50 backdrop-blur-sm z-50">

                    <div class="bg-white w-full max-w-md rounded-3xl
                                shadow-2xl p-8 animate-fadeIn">

                        <h2 class="text-xl font-bold mb-4 text-gray-800">
                            Eliminar Área
                        </h2>

                        <p class="text-gray-600 mb-6">
                            Tem certeza que deseja eliminar a área
                            <strong>{{ $area->nome }}</strong>?
                        </p>

                        <form method="POST"
                              action="{{ route('areasCientificas.destroy',$area->id) }}" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                            @csrf
                            @method('DELETE')

                            <div class="flex justify-end gap-3">

                                <button type="button"
                                    onclick="closeModal('deleteModal{{ $area->id }}')"
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
        {{ $areas->links() }}
    </div>


    <!-- MODAL CREATE -->
    <div id="createModal"
         class="fixed inset-0 hidden items-center justify-center
                bg-black/50 backdrop-blur-sm z-50">

        <div class="bg-white w-full max-w-md rounded-3xl
                    shadow-2xl p-8 animate-fadeIn">

            <h2 class="text-xl font-bold mb-6 text-gray-800">
                Nova Área Científica
            </h2>

            <form method="POST"
                  action="{{ route('areasCientificas.store') }}"    onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                @csrf

                <label class="block text-sm font-semibold mb-2">
                    Nome
                </label>

                <input type="text"
       name="nome"
      
       required
       minlength="3"
       maxlength="100"
       pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$"
       title="O nome deve possuir entre 3 e 100 caracteres e conter apenas letras, números e espaços."
       class="w-full px-4 py-3 rounded-xl border
              focus:ring-2 focus:ring-blue-500
              focus:outline-none">

<p class="text-xs text-gray-500 mt-2">
    Mínimo de 3 caracteres. Apenas letras, números e espaços.
</p>

                <label class="block text-sm font-semibold mb-2">
                    Descrição
                </label>

                <textarea name="descricao"
                          class="w-full px-4 py-3 rounded-xl border
                                 focus:ring-2 focus:ring-blue-500"></textarea>

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