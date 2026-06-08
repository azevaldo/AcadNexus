@extends('app.private.dashboard.app..templete')

@section('title','Adicionar Campanha')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">
 
<!-- HEADER -->
<div class="flex items-start gap-4 mb-6">
    {{-- Botão Voltar para as Campanhas do Curso --}}
    <a href="{{ route('cursos.campanhas', $curso->id) }}" 
       class="mt-1 flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm shrink-0"
       title="Voltar para Campanhas do Curso">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div>
        <h1 class="text-2xl font-semibold text-gray-800 leading-tight">
            Adicionar Campanha
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Crie uma nova campanha no sistema
        </p>
    </div>
</div>


<form method="POST"
      action="{{ route('campanhas.store') }}"
      class="bg-white border border-gray-200 rounded-xl"    onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">

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
