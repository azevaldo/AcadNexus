@extends('app.private.dashboard.app..templete')

@section('title','Adicionar Campanha')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">
 
<!-- HEADER -->
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">
        Adicionar Campanha
    </h1>
    <p class="text-sm text-gray-500 mt-1">
        Crie uma nova campanha no sistema
    </p>
</div>


<form method="POST"
      action="{{ route('campanhas.store') }}"
      class="bg-white border border-gray-200 rounded-xl">

    @csrf

    <div class="p-6 space-y-8">

        <!-- CAMPANHA -->
        <div>

            <h2 class="text-sm font-semibold text-gray-700 mb-4">
                Informações da Campanha
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
<div class="md:col-span-2">
                    <label class="text-sm text-gray-600">
                        Curso
                    </label>
 <input type="text"
                           name="curso_id"
                           class="w-full mt-1 px-3 py-2 border rounded-lg
                                  focus:ring-2 focus:ring-blue-500"
                            value="{{$curso->id}}"
                                  hidden>
                    <input type="text"
                           name=""
                           class="w-full mt-1 px-3 py-2 border rounded-lg
                                  focus:ring-2 focus:ring-blue-500"
                            value="{{$curso->nome}}"
                                  readonly>
                </div>
                <!-- CURSO -->
 

                <!-- TITULO -->
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-600">
                        Título
                    </label>

                    <input type="text"
                           name="titulo"
                           class="w-full mt-1 px-3 py-2 border rounded-lg
                                  focus:ring-2 focus:ring-blue-500"
                           required>
                </div>


                <!-- DESCRIÇÃO -->
                <div class="md:col-span-2">

                    <label class="text-sm text-gray-600">
                        Descrição
                    </label>

                    <textarea name="descricao"
                              rows="4"
                              class="w-full mt-1 px-3 py-2 border rounded-lg
                                     focus:ring-2 focus:ring-blue-500"
                              required></textarea>

                </div>


                <!-- PRAZO -->
                <div>
                    <label class="text-sm text-gray-600">
                        Prazo de Submissão
                    </label>

                    <input type="date"
                           name="prazo_submissao"
                           class="w-full mt-1 px-3 py-2 border rounded-lg
                                  focus:ring-2 focus:ring-blue-500">
                </div>


             

            </div>

        </div>

    </div>


    <!-- FOOTER -->
    <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">

        <a href="{{ route('campanhas.index') }}"
           class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
            Cancelar
        </a>

        <button type="submit"
            class="px-5 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">

            Salvar Campanha

        </button>

    </div>

</form>
 

</div>

@endsection
