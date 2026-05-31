 @extends('app.private.dashboard.app..templete')

@section('title','Adicionar Campanha')

@section('content')

<div class="max-w-5xl mx-auto px-8 py-10">

    <!-- HEADER -->
    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            Adicionar Campanha
        </h1>

        <p class="text-gray-500 mt-2">
            Crie uma nova campanha para submissão de anteprojetos
        </p>

    </div>


    <form method="POST"
          action="{{ route('campanhas.store') }}"
          class="bg-white border border-gray-200 rounded-3xl shadow-xl overflow-hidden" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">

        @csrf

        <div class="p-8 space-y-8">

            <!-- ERROS -->
            @if ($errors->any())

                <div class="bg-red-50 border border-red-200 rounded-2xl p-5">

                    <div class="flex items-center gap-2 mb-3">

                        <span class="text-red-600 text-lg">⚠️</span>

                        <h2 class="font-semibold text-red-700">
                            Corrija os seguintes erros
                        </h2>

                    </div>

                    <ul class="space-y-2 text-sm text-red-600">

                        @foreach ($errors->all() as $error)

                            <li>• {{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <!-- INFORMAÇÕES -->
            <div>

                <h2 class="text-lg font-semibold text-gray-800 mb-6">
                    Informações da Campanha
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- CURSO -->
                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Curso
                        </label>

                        <select
                            name="curso_id"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-2xl
                                   focus:ring-4 focus:ring-blue-500/20
                                   focus:border-blue-500 outline-none transition">

                            <option value="">
                                Selecionar curso
                            </option>

                            @foreach($cursos as $curso)

                                <option value="{{ $curso->id }}"
                                    {{ old('curso_id') == $curso->id ? 'selected' : '' }}>

                                    {{ $curso->nome }} - {{ $curso->departamento->nome }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- TITULO -->
                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Título da Campanha
                        </label>

                        <input
                            type="text"
                            name="titulo"
                            required
                            minlength="10"
                            maxlength="255"
                            value="{{ old('titulo') }}"
                            placeholder="Ex: Campanha de Submissão de Anteprojetos 2026"
                            class="w-full px-4 py-3 border border-gray-300 rounded-2xl
                                   focus:ring-4 focus:ring-blue-500/20
                                   focus:border-blue-500 outline-none transition">

                        <p class="text-xs text-gray-400 mt-2">
                            O título deve ter no mínimo 10 caracteres
                        </p>

                    </div>


                    <!-- DESCRIÇÃO -->
                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Descrição
                        </label>

                        <textarea
                            name="descricao"
                            rows="6"
                            required
                            minlength="20"
                            maxlength="5000"
                            placeholder="Descreva os objetivos e detalhes da campanha..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-2xl
                                   focus:ring-4 focus:ring-blue-500/20
                                   focus:border-blue-500 outline-none transition resize-none">{{ old('descricao') }}</textarea>

                        <p class="text-xs text-gray-400 mt-2">
                            A descrição deve ter no mínimo 20 caracteres
                        </p>

                    </div>


                    <!-- PRAZO -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Prazo de Submissão
                        </label>

                        <input
                            type="date"
                            name="prazo_submissao"
                            required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            value="{{ old('prazo_submissao') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-2xl
                                   focus:ring-4 focus:ring-blue-500/20
                                   focus:border-blue-500 outline-none transition">

                        <p class="text-xs text-gray-400 mt-2">
                            A data deve ser superior à data atual
                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- FOOTER -->
        <div class="flex justify-end gap-4 px-8 py-5 border-t bg-gray-50">

            <a href="{{ route('campanhas.index') }}"
               class="px-6 py-3 rounded-2xl bg-gray-200
                      hover:bg-gray-300 transition font-medium">

                Cancelar

            </a>

            <button type="submit"
                class="px-8 py-3 rounded-2xl bg-blue-600
                       hover:bg-blue-700 transition text-white
                       font-semibold shadow-lg shadow-blue-500/20">

                Salvar Campanha

            </button>

        </div>

    </form>

</div>

@endsection