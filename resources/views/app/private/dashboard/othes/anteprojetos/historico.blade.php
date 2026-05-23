 @extends('app.private.dashboard.app.templete')

@section('title', 'Dossiê do Anteprojeto')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10" 
     x-data="{ 
        openFeedbackModal: false, 
        versaoId: '', 
        statusSelecionado: '' 
     }">
    
    {{-- HEADER DE CONTEXTO --}}
    <div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between bg-white p-6 sm:p-10 rounded-[3rem] border border-slate-100 shadow-xl shadow-slate-200/50 gap-8">
        <div class="flex items-start sm:items-center gap-6">
            <a href=" " 
               class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all shadow-sm border border-slate-100">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <span class="px-3 py-1 bg-slate-900 text-white text-[10px] font-black uppercase rounded-lg tracking-wider">ID #{{ $anteprojeto->id }}</span>
                    <span class="text-slate-300">/</span>
                    <span class="text-[10px] text-blue-600 font-black uppercase tracking-[0.2em]">Dossiê Acadêmico</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">{{ $anteprojeto->titulo }}</h1>
                <div class="flex flex-wrap items-center gap-y-2 gap-x-4 mt-3 text-sm font-medium text-slate-500">
                    <span class="flex items-center gap-2"><i class="far fa-user text-blue-500"></i> {{ $anteprojeto->estudante->user->name }}</span>
                    <span class="hidden sm:inline text-slate-300">|</span>
                    <span class="flex items-center gap-2"><i class="fas fa-graduation-cap text-indigo-500"></i> {{ $anteprojeto->estudante->curso->nome }}</span>
                </div>
            </div>
        </div>

        {{-- STATUS GLOBAL --}}
        <div class="flex flex-col items-center lg:items-end gap-3 border-t lg:border-t-0 pt-6 lg:pt-0">
            @php
                $statusColors = [
                    'submetido' => 'bg-blue-600',
                    'aprovado'  => 'bg-emerald-500',
                    'rejeitado' => 'bg-red-500',
                    'melhorias' => 'bg-amber-500'
                ];
            @endphp
            <div class="flex items-center gap-3 bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100">
                <div class="w-3 h-3 rounded-full {{ $statusColors[$anteprojeto->status] ?? 'bg-slate-400' }} animate-pulse"></div>
                <span class="text-xs font-black uppercase text-slate-700 tracking-widest">Status: {{ $anteprojeto->status }}</span>
            </div>
        </div>
    </div>

    {{-- TIMELINE DE VERSÕES --}}
    <div class="relative">
        <div class="absolute left-8 top-0 bottom-0 w-1 bg-slate-100 hidden md:block"></div>

        <div class="space-y-10">
            @foreach($anteprojeto->versoes()->orderBy('numero_versao', 'desc')->get() as $v)
            <div class="relative md:pl-24">
                {{-- Indicador de Versão --}}
                <div class="absolute left-0 top-0 hidden md:flex w-16 h-16 rounded-2xl bg-white border-4 border-slate-50 shadow-sm items-center justify-center z-10">
                    <span class="text-lg font-black text-slate-900 uppercase">V{{ $v->numero_versao }}</span>
                </div>

                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden group hover:border-blue-200 transition-all duration-300">
                    <div class="grid grid-cols-1 lg:grid-cols-12">
                        
                        <div class="lg:col-span-8 p-6 sm:p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="md:hidden w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-xs">V{{ $v->numero_versao }}</div>
                                    <div>
                                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Submetido em</h3>
                                        <p class="text-xs text-slate-400 font-bold">{{ $v->created_at->format('d \d\e F, Y \à\s H:i') }}</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/'.$v->arquivo) }}" target="_blank" 
                                   class="flex items-center gap-3 bg-red-50 text-red-600 px-5 py-3 rounded-xl text-[10px] font-black uppercase hover:bg-red-600 hover:text-white transition-all">
                                    <i class="fas fa-file-pdf text-base"></i> Abrir Versão PDF
                                </a>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <i class="fas fa-comment-alt text-blue-500"></i> Comentário do Estudante
                                </h4>
                                <p class="text-sm text-slate-600 leading-relaxed italic">
                                    "{{ $v->descricao ?? 'Nenhuma descrição fornecida pelo estudante nesta etapa.' }}"
                                </p>
                            </div>
                        </div>

                        {{-- Coluna de Decisão/Feedback --}}
                        <div class="lg:col-span-4 bg-slate-50/50 border-l border-slate-100 p-6 sm:p-8">
                            @if($v->decisao)
                                <div class="h-full flex flex-col">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Parecer Técnico</span>
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase border 
                                            {{ $v->decisao->decisao == 'aprovado' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 
                                               ($v->decisao->decisao == 'melhorias' ? 'bg-amber-50 border-amber-100 text-amber-600' : 'bg-red-50 border-red-100 text-red-600') }}">
                                            {{ $v->decisao->decisao }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 flex-grow leading-relaxed mb-6">
                                        {{ $v->decisao->mensagem }}
                                    </p>

                                    {{-- Listagem de melhorias apontadas (se houver) --}}
                                    @if($v->decisao->itensMelhoria->count() > 0)
                                    <div class="mb-6 space-y-2">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Melhorias Solicitadas:</span>
                                        @foreach($v->decisao->itensMelhoria as $item)
                                            <div class="flex items-center gap-2 text-[10px] text-amber-700 bg-amber-50/50 p-2 rounded-lg border border-amber-100">
                                                <i class="fas fa-caret-right"></i> {{ $item->titulo }}
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif

                                    <div class="flex items-center gap-3 pt-4 border-t border-slate-200/50">
                                        <div class="w-8 h-8 bg-white rounded-lg shadow-sm flex items-center justify-center text-[10px] font-bold text-slate-400">
                                            {{ substr($v->decisao->coordenador->user->name ?? 'A', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black text-slate-700 uppercase leading-none">Prof. {{ $v->decisao->coordenador->user->name ?? 'Avaliador' }}</span>
                                            <span class="text-[9px] text-slate-400 font-medium">Responsável</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="h-full flex flex-col items-center justify-center text-center py-8">
                                    <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-slate-200 mb-4">
                                        <i class="fas fa-gavel text-xl"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Aguardando Avaliação</p>
                                    @hasanyrole('coordenador|admin')
                                    <button @click="openFeedbackModal = true; versaoId = '{{ $v->id }}'; statusSelecionado = ''" 
                                            class="w-full bg-slate-900 text-white py-3 rounded-xl text-[10px] font-black uppercase hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                                        Avaliar Versão {{ $v->numero_versao }}
                                    </button>
                                    @endhasanyrole      
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- MODAL DE AVALIAÇÃO --}}
    <div x-show="openFeedbackModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md"
         x-cloak x-transition>
        
        <div class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden" @click.away="openFeedbackModal = false">
            <form action="{{ route('anteprojetosAdmin.storeAvaliacao', $anteprojeto->id) }}" method="POST">
                @csrf
                <input type="hidden" name="anteprojeto_versao_id" :value="versaoId">

                <div class="p-8 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Parecer Técnico</h3>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Conclusão técnica da etapa</p>
                    </div>
                    <button type="button" @click="openFeedbackModal = false" class="text-slate-300 hover:text-slate-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    {{-- Seleção de Decisão --}}
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" name="decisao" value="aprovado" x-model="statusSelecionado" class="sr-only peer" required>
                            <div class="flex flex-col items-center p-4 rounded-2xl border-2 border-slate-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                <i class="fas fa-check-circle text-slate-200 group-hover:text-emerald-500 peer-checked:text-emerald-500 mb-2 text-xl"></i>
                                <span class="text-[9px] font-black uppercase text-slate-400 peer-checked:text-emerald-700">Aprovar</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="decisao" value="melhorias" x-model="statusSelecionado" class="sr-only peer">
                            <div class="flex flex-col items-center p-4 rounded-2xl border-2 border-slate-100 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all">
                                <i class="fas fa-edit text-slate-200 group-hover:text-amber-500 peer-checked:text-amber-500 mb-2 text-xl"></i>
                                <span class="text-[9px] font-black uppercase text-slate-400 peer-checked:text-amber-700">Melhorias</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="decisao" value="rejeitado" x-model="statusSelecionado" class="sr-only peer">
                            <div class="flex flex-col items-center p-4 rounded-2xl border-2 border-slate-100 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                <i class="fas fa-times-circle text-slate-200 group-hover:text-red-500 peer-checked:text-red-500 mb-2 text-xl"></i>
                                <span class="text-[9px] font-black uppercase text-slate-400 peer-checked:text-red-700">Rejeitar</span>
                            </div>
                        </label>
                    </div>

                    {{-- Lista de Melhorias (Visível apenas se clicar em melhorias) --}}
                    <div x-show="statusSelecionado === 'melhorias'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-4"
                         class="space-y-4 bg-amber-50/50 p-6 rounded-3xl border border-amber-100">
                        
                        <label class="text-[10px] font-black text-amber-600 uppercase tracking-widest block mb-2">Itens do Catálogo de Melhorias</label>
                        
                        <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($itensMelhoria as $item)
                            <label class="relative flex items-start p-3 rounded-xl bg-white border border-slate-100 cursor-pointer hover:border-amber-300 transition-all">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="melhorias[]" value="{{ $item->id }}" 
                                           class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500">
                                </div>
                                <div class="ml-3 text-xs">
                                    <span class="font-bold text-slate-700 block">{{ $item->titulo }}</span>
                                    <span class="text-slate-400 text-[10px]">{{ Str::limit($item->descricao, 50) }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Campo de Texto (Sempre visível) --}}
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-3">Observações Adicionais</label>
                        <textarea name="mensagem" rows="4" required
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-5 text-sm text-slate-700 focus:ring-0 focus:border-blue-500 transition-all outline-none"
                            placeholder="Descreva detalhadamente os pontos observados..."></textarea>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 border-t border-slate-100">
                    <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-blue-600 transition-all shadow-xl shadow-slate-200">
                        Finalizar e Notificar Aluno
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

@endsection