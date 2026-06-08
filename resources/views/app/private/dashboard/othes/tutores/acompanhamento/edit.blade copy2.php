 @extends('app.private.dashboard.app.templete')

@section('title', 'Editar Cronograma')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    
   <div class="flex items-start gap-4 mb-8">
    {{-- Botão Voltar Dinâmico --}}
    @hasanyrole('coordenador|professor|admin')
    <a href="{{ route('tutor.acompanhamento.show', $acompanhamento) }}" 
       class="mt-1 flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm shrink-0"
       title="Voltar para Acompanhamento">
        <i class="fas fa-arrow-left"></i>
    </a>
    @endhasanyrole
@role('estudante')
        <a href="{{ route('estudante.acompanhamento.show', $acompanhamento->id) }}" 
       class="mt-1 flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm shrink-0"
       title="Voltar para Acompanhamento">
        <i class="fas fa-arrow-left"></i>
    </a>
    @endrole
 

    <div>
        <h1 class="text-3xl font-black text-gray-800 uppercase tracking-tight leading-tight">
            Editar Cronograma de TCC
        </h1>
        <p class="text-gray-500 mt-1 text-sm">
            Estudante: <span class="font-bold text-indigo-600">{{ $acompanhamento->estudante->user->name }}</span>
        </p>
    </div>
</div>

    <div x-data="{ 
        fases: {{ json_encode($fasesEdit) }},
        addFase() {
            let lastFim = this.fases.length > 0 ? this.fases[this.fases.length - 1].mes_fim : 0;
            this.fases.push({
                id: null, 
                fase: '', 
                descricao: '', 
                mes_inicio: parseInt(lastFim) + 1, 
                mes_fim: parseInt(lastFim) + 1
            });
        },
        removeFase(index) {
            if(confirm('Deseja remover esta fase? Se ela já estiver salva no banco, será excluída permanentemente ao salvar.')) {
                this.fases.splice(index, 1);
            }
        }
    }">
        <form action="{{ route('tutor.acompanhamento.update', $acompanhamento->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Cabeçalho de Informações Reais do Acompanhamento --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="p-4 bg-indigo-600 rounded-2xl shadow-lg shadow-indigo-100 col-span-1 md:col-span-2">
                    <label class="text-[10px] font-black text-indigo-200 uppercase mb-2 block">Mês de Início do TCC (Referência)</label>
                    <input type="month" name="mes_referencia" required 
    value="{{ $acompanhamento->mes_referencia ? date('Y-m', strtotime($acompanhamento->mes_referencia)) : '' }}"
    class="w-full bg-indigo-500 text-white border-none rounded-xl px-4 py-2 focus:ring-2 focus:ring-white outline-none font-bold">
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tutor</p>
                    <p class="text-xs font-bold text-slate-700">{{ $acompanhamento->tutor->user->name ?? 'Não atribuído' }}</p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Anteprojeto</p>
                    <p class="text-xs font-bold text-slate-700 truncate">{{ $acompanhamento->anteprojeto->titulo ?? 'Sem Título' }}</p>
                </div>
            </div>

            <div class="bg-white shadow-2xl rounded-[2rem] border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-black text-gray-700 uppercase tracking-tighter">Ajustar Fases do Cronograma</h3>
                    <button type="button" x-on:click="addFase()" 
                        class="px-4 py-2 bg-indigo-600 text-white text-xs font-black rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                        <i class="fas fa-plus mr-2"></i> ADICIONAR FASE
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    <template x-for="(fase, index) in fases" :key="index">
                        <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm relative transition-all hover:border-indigo-200">
                            
                            {{-- Input oculto do ID da fase para o updateOrCreate do controller funcionar --}}
                            <input type="hidden" :name="'fases['+index+'][id]'" x-model="fase.id">

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

                            {{-- Botão Remover Fase --}}
                            <button type="button" x-on:click="removeFase(index)" x-show="fases.length > 1"
                                class="absolute -top-3 -right-3 bg-white border border-red-100 text-red-500 hover:bg-red-500 hover:text-white transition-all w-8 h-8 rounded-full shadow-sm">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="p-8 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-gray-400 max-w-sm italic">
                        * O sistema recalculará dinamicamente os prazos com base em qualquer mudança feita no mês de referência.
                    </p>
                    <div class="flex gap-3 w-full md:w-auto">
                        <a href="{{ route('tutor.acompanhamento.show', $acompanhamento->id) }}" 
                           class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-900 transition flex items-center">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full md:w-auto px-10 py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-indigo-600 transition shadow-xl uppercase tracking-widest text-sm">
                            Atualizar Cronograma
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection