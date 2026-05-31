@extends('app.private.dashboard.app.templete')

@section('title','Editar Campanha')

@section('content')

<div class="max-w-5xl mx-auto px-8 py-10">

    <!-- HEADER -->
    <div class="flex items-start gap-4 mb-8">
    {{-- Botão Voltar Minimalista --}}
    <a href="{{ route('campanhas.index') }}" 
       class="mt-1 flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm shrink-0"
       title="Voltar para Campanhas">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div>
        <h1 class="text-3xl font-bold text-gray-800 leading-tight">
            Editar Campanha
        </h1>
        <p class="text-gray-500 mt-1 text-sm">
            Atualize as informações e prazos da campanha
        </p>
    </div>
</div>


    <form method="POST"
          action="{{ route('campanhas.update',$campanha->id) }}"
          class="bg-white border border-gray-200 rounded-3xl shadow-xl overflow-hidden" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">

        @csrf
        @method('PUT')


        <div class="p-8 space-y-10">

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
                    Informações Gerais
                </h2>

                <div class="grid grid-cols-1 gap-6">

                    <!-- TITULO -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Título da Campanha
                        </label>

                        <input
                            type="text"
                            name="titulo"
                            required
                            minlength="10"
                            maxlength="255"
                            value="{{ old('titulo',$campanha->titulo) }}"
                            placeholder="Digite o título da campanha"
                            class="w-full px-5 py-4 border border-gray-300 rounded-2xl
                                   focus:ring-4 focus:ring-blue-500/20
                                   focus:border-blue-500 outline-none transition">

                        <p class="text-xs text-gray-400 mt-2">
                            O título deve possuir no mínimo 10 caracteres
                        </p>

                    </div>


                    <!-- DESCRIÇÃO -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Descrição
                        </label>

                        <textarea
                            name="descricao"
                            rows="6"
                            required
                            minlength="10"
                            maxlength="5000"
                            placeholder="Descreva os detalhes da campanha..."
                            class="w-full px-5 py-4 border border-gray-300 rounded-2xl
                                   focus:ring-4 focus:ring-blue-500/20
                                   focus:border-blue-500 outline-none transition resize-none">{{ old('descricao',$campanha->descricao) }}</textarea>

                        <p class="text-xs text-gray-400 mt-2">
                            A descrição deve possuir no mínimo 10 caracteres
                        </p>

                    </div>

                </div>

            </div>


            <!-- PRAZOS -->
            <div>

                <h2 class="text-lg font-semibold text-gray-800 mb-6">
                    Prazos da Campanha
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- SUBMISSÃO -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Prazo de Submissão
                        </label>

                        <input
                            type="date"
                            name="prazo_submissao"
                            required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            value="{{ old('prazo_submissao',$campanha->prazo_submissao) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-2xl
                                   focus:ring-4 focus:ring-blue-500/20
                                   focus:border-blue-500 outline-none transition">

                    </div>


                    <!-- MELHORIAS -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Prazo de Melhorias
                        </label>

                        <input
                            type="date"
                            name="prazo_melhorias"
                            value="{{ old('prazo_melhorias',$campanha->prazo_melhorias) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-2xl
                                   focus:ring-4 focus:ring-blue-500/20
                                   focus:border-blue-500 outline-none transition">

                    </div>


                    <!-- NOVO PRAZO -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Novo Prazo
                        </label>

                        <input
                            type="date"
                            name="prazo_novo"
                            value="{{ old('prazo_novo',$campanha->prazo_novo) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-2xl
                                   focus:ring-4 focus:ring-blue-500/20
                                   focus:border-blue-500 outline-none transition">

                    </div>

                </div>

            </div>


            <!-- STATUS -->
            <div>

                <h2 class="text-lg font-semibold text-gray-800 mb-6">
                    Status da Campanha
                </h2>

                <div class="max-w-md">

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-2xl
                               focus:ring-4 focus:ring-blue-500/20
                               focus:border-blue-500 outline-none transition">

                        <option value="aberta"
                            {{ old('status',$campanha->status) == 'aberta' ? 'selected' : '' }}>
                            Aberta
                        </option>

                        <option value="encerrada"
                            {{ old('status',$campanha->status) == 'encerrada' ? 'selected' : '' }}>
                            Encerrada
                        </option>

                        <option value="cancelada"
                            {{ old('status',$campanha->status) == 'cancelada' ? 'selected' : '' }}>
                            Cancelada
                        </option>

                    </select>

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

                Atualizar Campanha

            </button>

        </div>

    </form>

</div>

@endsection