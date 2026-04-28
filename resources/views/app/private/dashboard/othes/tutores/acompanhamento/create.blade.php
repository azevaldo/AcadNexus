 @extends('app.private.dashboard.app..templete')

@section('title', 'Iniciar Acompanhamento')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tight">Configurar Cronograma de TCC</h1>
        <p class="text-gray-500">Estudante: <span class="font-bold text-indigo-600">{{ $estudante->user->name }}</span></p>
    </div>

    <div x-data="{ 
        fases: [{fase: 'Entrega do Capítulo 1', descricao: '', inicio: '', fim: ''}],
        addFase() {
            this.fases.push({fase: '', descricao: '', inicio: '', fim: ''});
        },
        removeFase(index) {
            this.fases.splice(index, 1);
        }
    }">
        <form action="{{ route('tutor.acompanhamento.store', $estudante->id) }}" method="POST">
            @csrf
            
            <input type="hidden" name="tutor_id" value="{{ $vinculo->tutor_id }}">
            <input type="hidden" name="co_tutor_id" value="{{ $vinculo->co_tutor_id }}">
            <input type="hidden" name="anteprojeto_id" value="{{ $anteprojeto->id }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Anteprojeto Identificado</p>
                    <p class="text-sm font-bold text-slate-700 truncate">{{ $anteprojeto->titulo }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tutor Principal</p>
                    <p class="text-sm font-bold text-slate-700">{{ $vinculo->tutor->professor->user->name }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Co-Tutor</p>
                    <p class="text-sm font-bold text-slate-700">{{ $vinculo->coTutor->professor->user->name ?? 'Nenhum' }}</p>
                </div>
            </div>

            <div class="bg-white shadow-2xl rounded-[2rem] border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-gray-700 uppercase tracking-tighter">Definição de Fases</h3>
                    <button type="button" x-on:click="addFase()" 
                        class="px-4 py-2 bg-indigo-600 text-white text-xs font-black rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 cursor-pointer">
                        <i class="fas fa-plus mr-2"></i> ADICIONAR FASE
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    <template x-for="(fase, index) in fases" :key="index">
                        <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm relative transition-all">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Nome da Fase</label>
                                    <input type="text" 
                                           :name="'fases['+index+'][fase]'" 
                                           x-model="fase.fase" 
                                           required
                                           placeholder="Ex: Revisão de Literatura"
                                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Data Início</label>
                                    <input type="date" :name="'fases['+index+'][inicio]'" x-model="fase.inicio" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Data Fim (Prazo)</label>
                                    <input type="date" :name="'fases['+index+'][fim]'" x-model="fase.fim" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Descrição (Opcional)</label>
                                    <textarea :name="'fases['+index+'][descricao]'" x-model="fase.descricao" rows="2"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none"></textarea>
                                </div>
                            </div>

                            <button type="button" x-on:click="removeFase(index)" x-show="fases.length > 1"
                                class="absolute top-4 right-4 text-gray-300 hover:text-red-500 transition-colors p-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="p-8 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-indigo-600 transition shadow-xl uppercase tracking-widest text-sm">
                        Salvar e Iniciar Acompanhamento
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection