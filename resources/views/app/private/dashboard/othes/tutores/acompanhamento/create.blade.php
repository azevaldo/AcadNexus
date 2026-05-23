@extends('app.private.dashboard.app.templete')

@section('title', 'Iniciar Acompanhamento')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tight">Configurar Cronograma de TCC</h1>
        <p class="text-gray-500">Estudante: <span class="font-bold text-indigo-600">{{ $estudante->user->name }}</span></p>
    </div>

    <div x-data="{ 
        fases: [{fase: '', descricao: '', mes_inicio: 1, mes_fim: 1}],
        addFase() {
            let lastFim = this.fases[this.fases.length - 1].mes_fim;
            this.fases.push({fase: '', descricao: '', mes_inicio: parseInt(lastFim) + 1, mes_fim: parseInt(lastFim) + 1});
        },
        removeFase(index) {
            this.fases.splice(index, 1);
        }
    }">
        <form action="{{ route('tutor.acompanhamento.storeFases', $acompanhamento->id) }}" method="POST">
            @csrf
            
            <input type="hidden" name="tutor_id" value="{{ $vinculo->tutor_id }}">
            <input type="hidden" name="co_tutor_id" value="{{ $vinculo->co_tutor_id }}">
            <input type="hidden" name="anteprojeto_id" value="{{ $anteprojeto->id }}">

            {{-- Cabeçalho de Informações --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="p-4 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-100 col-span-1 md:col-span-2">
                    <label class="text-[10px] font-black text-indigo-200 uppercase mb-2 block">Mês de Início do TCC (Referência)</label>
                    <input type="month" name="mes_referencia" required 
                        class="w-full bg-indigo-500 text-white border-none rounded-xl px-4 py-2 focus:ring-2 focus:ring-white outline-none font-bold">
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tutor</p>
                    <p class="text-xs font-bold text-slate-700">{{ $vinculo->tutor->user->name }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Anteprojeto</p>
                    <p class="text-xs font-bold text-slate-700 truncate">{{ $anteprojeto->titulo }}</p>
                </div>
            </div>

            <div class="bg-white shadow-2xl rounded-[2rem] border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-gray-700 uppercase tracking-tighter">Fases do Cronograma</h3>
                    <button type="button" x-on:click="addFase()" 
                        class="px-4 py-2 bg-indigo-600 text-white text-xs font-black rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        <i class="fas fa-plus mr-2"></i> ADICIONAR FASE
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    <template x-for="(fase, index) in fases" :key="index">
                        <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm relative transition-all hover:border-indigo-200">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                {{-- Nome da Fase --}}
                                <div class="md:col-span-6">
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Nome da Fase</label>
                                    <input type="text" :name="'fases['+index+'][fase]'" x-model="fase.fase" required
                                        placeholder="Ex: Entrega do Capítulo 1"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>

                                {{-- Mês Início --}}
                                <div class="md:col-span-3">
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Mês Início</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" :name="'fases['+index+'][mes_inicio]'" x-model="fase.mes_inicio" required min="1"
                                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-center">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Mês</span>
                                    </div>
                                </div>

                                {{-- Mês Fim --}}
                                <div class="md:col-span-3">
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Mês Fim</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" :name="'fases['+index+'][mes_fim]'" x-model="fase.mes_fim" required min="1"
                                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-center">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Mês</span>
                                    </div>
                                </div>

                                {{-- Descrição --}}
                                <div class="md:col-span-12">
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Descrição dos Objetivos</label>
                                    <textarea :name="'fases['+index+'][descricao]'" x-model="fase.descricao" rows="2"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none text-sm"></textarea>
                                </div>
                            </div>

                            {{-- Botão Remover --}}
                            <button type="button" x-on:click="removeFase(index)" x-show="fases.length > 1"
                                class="absolute -top-3 -right-3 bg-white border border-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-all w-8 h-8 rounded-full shadow-sm">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="p-8 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-gray-400 max-w-sm italic">
                        * O sistema calculará as datas reais automaticamente somando os meses ao mês de referência escolhido.
                    </p>
                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-indigo-600 transition shadow-xl uppercase tracking-widest text-sm">
                        Iniciar Acompanhamento
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection