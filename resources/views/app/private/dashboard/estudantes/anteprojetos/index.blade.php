 @extends('app.private.dashboard.app.templete')

@section('title', 'Meus Anteprojetos')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 font-sans antialiased bg-slate-50/50 min-h-screen">
    <div class="mb-4">
    <a href="{{ route('estudante.campanhaestudante.show', $campanha->id) }}" 
       class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors group">
        <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
        Voltar para Detalhes da Campanha
    </a>
</div>
    {{-- CABEÇALHO PRINCIPAL DA CAMPANHA --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/60 shadow-sm mb-8 transition-all">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                        Campanha Acadêmica
                    </span>
                    
                    {{-- STATUS DA CAMPANHA --}}
                    @if($campanha->status == 'aberta' || $campanha->status == 'ativa')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                            Ativa
                        </span>
                    @elseif($campanha->status == 'encerrada')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                            Encerrada
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-red-50 text-red-600 border border-red-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                            Cancelada
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">
                    {{ $campanha->titulo }}
                </h1>
                
                {{-- CRONOGRAMA INTEGRADO --}}
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 pt-2 text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                            <i class="far fa-calendar-alt"></i>
                        </div>
                        <span>Submissão até: <strong class="text-slate-800 font-semibold">{{ \Carbon\Carbon::parse($campanha->prazo_submissao)->format('d/m/Y') }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                            <i class="fas fa-tools"></i>
                        </div>
                        <span>Melhorias até: <strong class="text-slate-800 font-semibold">{{ $campanha->prazo_melhorias ? \Carbon\Carbon::parse($campanha->prazo_melhorias)->format('d/m/Y') : 'Não Definido' }}</strong></span>
                    </div>
                </div>
            </div>

            {{-- BOTÕES DE AÇÃO BASEADOS NAS REGRAS DE NEGÓCIO --}}
            <div class="flex items-center shrink-0">
                @if($campanha->status == "aberta")
                    @if($anteprojetos->isEmpty())
                        @if($fase1 == true)    
                            <a href="{{ route('estudante.anteprojetos.create', $campanha->id) }}" 
                               class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-xl font-bold shadow-md shadow-blue-500/10 transition-all flex items-center justify-center gap-2 group text-sm active:scale-[0.98]">
                                <i class="fas fa-plus transform group-hover:scale-110 transition-transform"></i> 
                                Iniciar Anteprojeto
                            </a>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 text-red-700 text-sm font-bold border border-red-100">
                                <i class="fas fa-calendar-times text-base"></i>
                                Submissões Encerradas
                            </span>
                        @endif
                    @else 
                        @php $ultimo = $anteprojetos->first(); @endphp
                               
                        @if($ultimo->status == 'aprovado')
                            <span class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-bold border border-emerald-100">
                                <i class="fas fa-check-circle text-base text-emerald-500 animate-pulse"></i>
                                Proposta Aprovada
                            </span>
                        @else
                            @if($campanha->text_melhorias ?? true) {{-- Fallback seguro para prazos --}}
                                @if($fase2 == true)
                                    @if($ultimo->status == 'melhorias')
                                        <a href="{{ route('estudante.anteprojetos.nova_versao', $ultimo->id) }}" 
                                           class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white px-6 py-3.5 rounded-xl font-bold shadow-md shadow-amber-500/10 transition-all flex items-center justify-center gap-2 group text-sm active:scale-[0.98]">
                                            <i class="fas fa-sync-alt transform group-hover:rotate-180 transition-transform duration-500"></i> 
                                            Submeter Ajustes
                                        </a>
                                    @elseif($ultimo->status == 'rejeitado' && $totalAnteprojetos == 1 && $totalVersoes == 1)
                                        <a href="{{ route('estudante.anteprojetos.create', $campanha->id) }}" 
                                           class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-xl font-bold shadow-md shadow-blue-500/10 transition-all flex items-center justify-center gap-2 group text-sm active:scale-[0.98]">
                                            <i class="fas fa-file-medical transform group-hover:scale-110 transition-transform"></i>    
                                            Nova Proposta
                                        </a>
                                    @elseif(($ultimo->status == 'rejeitado' && $totalAnteprojetos == 1 && $totalVersoes > 1) || ($ultimo->status == 'rejeitado' && $totalAnteprojetos > 1))
                                        <span class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-slate-100 text-slate-600 text-sm font-bold border border-slate-200">
                                            <i class="fas fa-lock text-slate-400"></i>
                                            Aguarde nova oportunidade
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-red-50 text-red-600 text-sm font-bold border border-red-100">
                                        <i class="fas fa-clock"></i>
                                        Prazo de Ajustes Esgotado
                                    </span>
                                @endif
                            @endif
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- CONTEÚDO PRINCIPAL (GRID) --}}
    @if($anteprojetos->isEmpty())
        {{-- ESTADO VAZIO LIMPO E CENTRADO --}}
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200/80 p-12 sm:p-20 text-center max-w-2xl mx-auto shadow-sm">
            <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-5 border border-slate-100">
                <i class="fas fa-folder-open text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Nenhum anteprojeto iniciado</h3>
            <p class="text-slate-500 mt-2 text-sm max-w-sm mx-auto leading-relaxed">
                Você ainda não submeteu nenhuma proposta de tema para esta campanha acadêmica.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- LISTA DE ANTEPROJETOS --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between px-2 mb-2">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                        Histórico de Submissões
                    </h2>
                    <span class="text-xs text-slate-400 font-medium">Total: {{ $anteprojetos->count() }}</span>
                </div>

                @foreach($anteprojetos as $projeto)
                    <div class="bg-white rounded-2xl p-5 border border-slate-200/60 shadow-sm hover:border-blue-200 hover:shadow-md/5 transition-all duration-200">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-blue-50/60 rounded-xl flex items-center justify-center text-blue-600 border border-blue-100/50 shrink-0">
                                    <i class="fas fa-file-invoice text-lg"></i>
                                </div>
                                
                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-base font-bold text-slate-900 leading-snug">
                                            {{ $projeto->titulo ?? 'Anteprojeto sem título' }}
                                        </h3>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-slate-100 text-slate-600 font-extrabold tracking-wide uppercase">
                                            V{{ $projeto->numero_versao }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-slate-500 text-sm line-clamp-2 pr-4 leading-relaxed">
                                        {{ $projeto->descricao }}
                                    </p>

                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 pt-3">
                                        {{-- STATUS TAGS REESTILIZADAS --}}
                                        @switch($projeto->status)
                                            @case('aprovado')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Aprovado
                                                </span>
                                                @break
                                            @case('rejeitado')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Rejeitado
                                                </span>
                                                @break
                                            @case('melhorias')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Requer Ajustes
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span> Em Análise
                                                </span>
                                        @endswitch

                                        <span class="text-slate-400 text-xs flex items-center gap-1.5">
                                            <i class="far fa-clock"></i> 
                                            {{ $projeto->created_at->format('d/m/Y \à\s H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('estudante.anteprojetos.show', $projeto->id) }}" 
                               class="bg-slate-50 hover:bg-blue-600 hover:text-white text-slate-400 hover:border-blue-600 p-3 rounded-xl transition-all border border-slate-200/60 flex items-center justify-center shrink-0 self-start" 
                               title="Visualizar Detalhes">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($anteprojetos->hasPages())
        <div class="px-6 py-5 bg-slate-50/50 border-t border-slate-100">
            {{ $anteprojetos->links() }}
        </div>
        @endif

            {{-- SIDEBAR LATERAL INFORMATIVA --}}
            <div class="space-y-6">
                {{-- CARD: PRÓXIMOS PASSOS --}}
                <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg border border-slate-800">
                    <h4 class="text-md font-bold mb-5 flex items-center gap-2 text-blue-400 tracking-wide">
                        <i class="fas fa-stream text-sm"></i> Fluxo do Processo
                    </h4>
                    
                    <div class="space-y-5 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-800">
                        <div class="flex gap-4 relative">
                            <span class="w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/40 font-black text-[11px] flex items-center justify-center shrink-0 z-10 bg-slate-900">1</span>
                            <div class="space-y-0.5">
                                <p class="text-xs font-bold text-slate-200">Revisão da Coordenação</p>
                                <p class="text-[11px] text-slate-400 leading-normal">Seu documento passará por avaliação de comitê científico.</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4 relative">
                            <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/40 font-black text-[11px] flex items-center justify-center shrink-0 z-10 bg-slate-900">2</span>
                            <div class="space-y-0.5">
                                <p class="text-xs font-bold text-slate-200">Atenção ao Status</p>
                                <p class="text-[11px] text-slate-400 leading-normal">Caso mude para "Requer Ajustes", corrija e reenvie no prazo estipulado.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 relative">
                            <span class="w-6 h-6 rounded-full bg-red-500/20 text-red-400 border border-red-500/40 font-black text-[11px] flex items-center justify-center shrink-0 z-10 bg-slate-900">3</span>
                            <div class="space-y-0.5">
                                <p class="text-xs font-bold text-slate-200">Casos de Rejeição</p>
                                <p class="text-[11px] text-slate-400 leading-normal">Se rejeitado em primeira instância, você ganha o direito de propor um novo tema.</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4 relative">
                            <span class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 font-black text-[11px] flex items-center justify-center shrink-0 z-10 bg-slate-900">4</span>
                            <div class="space-y-0.5">
                                <p class="text-xs font-bold text-slate-200">Fase de Orientação</p>
                                <p class="text-[11px] text-slate-400 leading-normal">Com a aprovação consolidada, o sistema libera a vinculação do seu tutor.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD: REGRAS E DIRETRIZES --}}
                <div class="bg-amber-50/60 rounded-3xl p-5 border border-amber-200/70 shadow-sm/5">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-amber-600 text-base mt-0.5"></i>
                        <div class="space-y-1">
                            <p class="text-amber-900 font-bold text-sm">Diretrizes de Submissão</p>
                            <p class="text-amber-700 text-xs leading-relaxed">
                                Certifique-se de que o arquivo carregado está exclusivamente em formato PDF e legível. Modificações ou envios fora dos prazos regulamentares não serão processados pelo sistema.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection