@extends('app.private.dashboard.app..templete')

@section('title', 'Editar Cronograma')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10 font-sans">
    
    <div class="mb-8 border-b-2 border-gray-100 pb-6 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 uppercase tracking-tight">Editar Cronograma de TCC</h1>
            <p class="text-sm text-gray-500 mt-1">Estudante: <span class="font-bold text-blue-600">{{ $acompanhamento->estudante->user->name }}</span></p>
        </div>
    </div>

    <div x-data="{ 
        fases: {{ $acompanhamento->fases->map(fn($f) => [
            'id' => $f->id,
            'fase' => $f->fase,
            'descricao' => $f->descricao,
            'inicio' => $f->inicio,
            'fim' => $f->fim
        ])->toJson() }},
        addFase() {
            this.fases.push({id: null, fase: '', descricao: '', inicio: '', fim: ''});
        },
        removeFase(index) {
            if(confirm('Deseja remover esta fase? Se ela já estiver salva, será excluída permanentemente.')) {
                this.fases.splice(index, 1);
            }
        }
    }">
        <form action="{{ route('tutor.acompanhamento.update', $acompanhamento->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white border-2 border-gray-900 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                <div class="p-8 border-b-2 border-gray-900 flex justify-between items-center bg-gray-50">
                    <h3 class="text-sm font-black text-gray-700 uppercase tracking-widest">Ajustar Etapas do Projeto</h3>
                    <button type="button" x-on:click="addFase()" 
                        class="px-4 py-2 bg-gray-900 text-white text-[10px] font-black rounded hover:bg-blue-700 transition uppercase tracking-widest">
                        + Adicionar Nova Fase
                    </button>
                </div>

                <div class="p-8 space-y-6 bg-white">
                    <template x-for="(fase, index) in fases" :key="index">
                        <div class="p-6 bg-white border-2 border-gray-100 rounded-lg relative group hover:border-gray-900 transition-colors">
                            
                            <input type="hidden" :name="'fases['+index+'][id]'" x-model="fase.id">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Nome da Etapa</label>
                                    <input type="text" :name="'fases['+index+'][fase]'" x-model="fase.fase" required
                                        class="w-full border-2 border-gray-200 rounded px-4 py-3 text-sm font-bold focus:border-gray-900 outline-none transition">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Data Início</label>
                                    <input type="date" :name="'fases['+index+'][inicio]'" x-model="fase.inicio" required
                                        class="w-full border-2 border-gray-200 rounded px-4 py-3 text-sm font-bold focus:border-gray-900 outline-none">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Data Fim (Prazo)</label>
                                    <input type="date" :name="'fases['+index+'][fim]'" x-model="fase.fim" required
                                        class="w-full border-2 border-gray-200 rounded px-4 py-3 text-sm font-bold focus:border-gray-900 outline-none">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Instruções</label>
                                    <textarea :name="'fases['+index+'][descricao]'" x-model="fase.descricao" rows="2"
                                        class="w-full border-2 border-gray-200 rounded px-4 py-3 text-sm font-medium focus:border-gray-900 outline-none"></textarea>
                                </div>
                            </div>

                            <button type="button" x-on:click="removeFase(index)"
                                class="absolute top-4 right-4 text-gray-300 hover:text-red-600 transition-colors p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div class="p-8 bg-gray-50 border-t-2 border-gray-900 flex justify-end gap-4">
                    <a href="{{ route('tutor.acompanhamento.show', $acompanhamento->id) }}" class="px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-900 transition">Cancelar</a>
                    <button type="submit" class="px-10 py-4 bg-gray-900 text-white font-black rounded shadow-[4px_4px_0px_0px_rgba(59,130,246,0.5)] hover:bg-blue-600 transition uppercase tracking-widest text-xs">
                        Atualizar Cronograma
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection