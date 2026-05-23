 @extends('app.private.dashboard.app.templete')

@section('title','Editar Campanha')

@section('content')

<div class="max-w-5xl mx-auto px-8 py-10">

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Editar Campanha
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Atualize as informações da campanha
        </p>
    </div>


    <form method="POST"
          action="{{ route('campanhas.update',$campanha->id) }}"
          class="bg-white border border-gray-200 rounded-2xl shadow-lg">

        @csrf
        @method('PUT')


        <div class="p-8 space-y-8">

            <!-- TÍTULO -->
            <div>

                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Título
                </label>

                <input type="text"
                       name="titulo"
                       value="{{ $campanha->titulo }}"
                       required
                       class="w-full px-4 py-3 rounded-xl border
                              focus:ring-2 focus:ring-blue-500
                              focus:outline-none">

            </div>


            <!-- DESCRIÇÃO -->
            <div>

                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Descrição
                </label>

                <textarea name="descricao"
                          rows="4"
                          class="w-full px-4 py-3 rounded-xl border
                                 focus:ring-2 focus:ring-blue-500
                                 focus:outline-none">{{ $campanha->descricao }}</textarea>

            </div>


            <!-- PRAZOS -->
            <div>

                <h2 class="text-sm font-semibold text-gray-700 mb-4">
                    Prazos da Campanha
                </h2>

                

            </div>


            <!-- STATUS -->
            <div>

                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Status
                </label>

                <select name="status"
                        class="w-full md:w-1/3 px-4 py-3 rounded-xl border
                               focus:ring-2 focus:ring-blue-500">

                    <option value="aberta"
                        {{ $campanha->status == 'aberta' ? 'selected' : '' }}>
                        Aberta
                    </option>

                    <option value="encerrada"
                        {{ $campanha->status == 'encerrada' ? 'selected' : '' }}>
                        Encerrada
                    </option>

                </select>

            </div>

        </div>


        <!-- FOOTER -->
        <div class="flex justify-end gap-4 px-8 py-5 border-t bg-gray-50 rounded-b-2xl">

            <a href="{{ route('campanhas.index') }}"
               class="px-5 py-2 rounded-xl bg-gray-200 hover:bg-gray-300">

                Cancelar

            </a>

            <button type="submit"
                    class="px-6 py-2 rounded-xl bg-blue-600
                           text-white hover:bg-blue-700 shadow">

                Atualizar Campanha

            </button>

        </div>

    </form>

</div>

@endsection