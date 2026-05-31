@extends('app.private.dashboard.app.templete')

@section('title', 'Acompanhamento de TCC')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

<div class="p-4 md:p-8 max-w-7xl mx-auto space-y-8 font-sans" x-data="{ abaAtiva: 0 }" x-cloak>

    <div class="bg-white shadow-sm border border-slate-100 rounded-[2rem] p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider mb-3">
                Painel do Estudante
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Acompanhamento de Orientação</h1>
           <p class="flex flex-wrap items-center gap-x-2 text-slate-500 text-sm mt-1">
    <span>
        Tutor: 
        <strong class="text-slate-700 font-bold">{{ $acompanhamento->tutor->user->name }}</strong>
        <a href="{{ route('professores.show', $acompanhamento->tutor->id) }}" 
           class="inline-flex items-center justify-center ml-1 text-slate-400 hover:text-blue-600 transition-colors align-middle" 
           title="Ver Perfil do Tutor">
            <i class="fas fa-info-circle text-xs"></i>
        </a>
    </span>

    @if($acompanhamento->coTutor) 
        <span class="text-slate-300">|</span>
        <span>
            Co-Tutor: 
            <strong class="text-slate-700 font-bold">{{ $acompanhamento->coTutor->user->name }}</strong>
            <a href="{{ route('professores.show', $acompanhamento->coTutor->id) }}" 
               class="inline-flex items-center justify-center ml-1 text-slate-400 hover:text-blue-600 transition-colors align-middle" 
               title="Ver Perfil do Co-Tutor">
                <i class="fas fa-info-circle text-xs"></i>
            </a>
        </span>
    @endif
</p>
            <p class="text-xs font-bold text-slate-400 uppercase mt-2">
                Início do Projeto: <span class="text-slate-600">{{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->translatedFormat('F / Y') }}</span>
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            @if(!$acompanhamento->fases->isEmpty())
                <a href="{{ route('tutor.acompanhamento.edit', $acompanhamento->id) }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-all shadow-2xs">
                    <i class="fas fa-calendar-alt text-blue-500"></i> Ajustar Cronograma
                </a>
            @endif

            @if($acompanhamento->fases->isEmpty())
                <a href="{{ route('tutor.acompanhamento.createFases', $acompanhamento->id) }}" 
                   class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-blue-700 transition-all shadow-sm">
                    <i class="fas fa-calendar-plus"></i> Fazer o Cronograma
                </a>
            @endif

            <a href="{{ route('estudante.acompanhamento.index') }}" 
               class="inline-flex items-center bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-3 rounded-xl font-bold transition-all text-xs border border-slate-200">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-4 space-y-3">
            <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 px-2 mb-1">Fases do Projeto</h2>
            
            @foreach($acompanhamento->fases as $index => $fase)
                <button type="button"
                        @click="abaAtiva = {{ $index }}" 
                        :class="abaAtiva === {{ $index }} ? 'border-blue-600 bg-blue-50/40 text-slate-900 shadow-xs ring-2 ring-blue-500/10' : 'border-slate-100 bg-white text-slate-500 hover:border-slate-200'"
                        class="w-full p-5 border rounded-2xl transition-all text-left outline-none relative block group">
                    
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-[10px] font-bold uppercase opacity-70">Mês de Início: {{ $fase->mes_inicio }}</span>
                        
                        <span @class([
                            'text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-md border',
                            'bg-emerald-50 text-emerald-700 border-emerald-200' => $fase->status == 'aprovado',
                            'bg-amber-50 text-amber-700 border-amber-200' => in_array($fase->status, ['pendente', 'submetido']),
                            'bg-indigo-50 text-indigo-700 border-indigo-200' => $fase->status == 'melhorias' || $fase->status == 'ajustes',
                            'bg-slate-50 text-slate-500 border-slate-200' => !in_array($fase->status, ['aprovado', 'pendente', 'submetido', 'melhorias', 'ajustes']),
                        ])>
                            {{ $fase->status }}
                        </span>
                    </div>

                    <p class="text-sm font-extrabold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $fase->fase }}</p>
                    
                    <p class="text-[11px] mt-2 font-semibold text-slate-400 flex items-center gap-1">
                        <i class="far fa-clock text-xs"></i> Prazo: 
                        <span class="text-slate-600">
                            {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('F / Y') }}
                        </span>
                    </p>
                </button>
            @endforeach
        </div>

        <div class="lg:col-span-8">
            @forelse($acompanhamento->fases as $index => $fase)
                <div x-show="abaAtiva === {{ $index }}" x-transition:enter="transition ease-out duration-200" class="space-y-6">
                    
                    <div class="bg-white shadow-sm border border-slate-100 rounded-[2rem] p-6 md:p-8">
                        
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6 pb-6 border-b border-slate-100">
                            <div>
                                <span class="text-[10px] font-extrabold bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md uppercase tracking-wide">
                                    Fase Detalhada
                                </span>
                                <h2 class="text-2xl font-black text-slate-800 mt-2">{{ $fase->fase }}</h2>
                            </div>
                            
                            <div class="flex gap-3">
                                <div class="bg-slate-50 p-2.5 border border-slate-100 rounded-xl text-center min-w-[100px]">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase block">Início</span>
                                    <span class="text-xs font-bold text-slate-700 uppercase">
                                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_inicio - 1)->translatedFormat('M / Y') }}
                                    </span>
                                </div>
                                <div class="bg-rose-50/50 p-2.5 border border-rose-100 rounded-xl text-center min-w-[100px]">
                                    <span class="text-[9px] font-bold text-rose-400 uppercase block">Entrega</span>
                                    <span class="text-xs font-bold text-rose-600 uppercase">
                                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('M / Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 p-4 bg-slate-50 border-l-4 border-slate-800 rounded-r-xl text-sm font-medium text-slate-600 leading-relaxed">
                            {{ $fase->descricao ?? 'Sem instruções específicas registradas.' }}
                        </div>

                        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                            
                            <div class="xl:col-span-5 space-y-4">
                                @if($fase->status !== 'aprovado')
                                    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-3xs">
                                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                                            <i class="fas fa-cloud-upload-alt text-blue-500"></i> Nova Submissão
                                        </h4>
                                        
                                        <form action="{{ route('estudante.acompanhamentos.storeSubmissao', $fase->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                                            @csrf
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Arquivo para o prazo de {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('M/Y') }}</label>
                                                <input type="file"
       name="arquivo"
       required
       accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
       class="w-full text-sm text-slate-500
              file:mr-3 file:py-2 file:px-4
              file:rounded-lg file:border-0
              file:text-xs file:font-bold
              file:bg-blue-50 file:text-blue-700
              hover:file:bg-blue-100
              border border-slate-200 rounded-xl
              p-1.5 bg-slate-50/50 cursor-pointer
              focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Comentários do Estudante</label>
                                                <textarea name="descricao_estudante" rows="3" required placeholder="Escreva informações sobre esta versão do arquivo..."
                                                          class="w-full text-sm border border-slate-200 rounded-xl p-3 bg-slate-50/50 focus:outline-none focus:border-blue-500 placeholder-slate-400"></textarea>
                                            </div>
                                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors text-xs uppercase tracking-wider shadow-xs">
                                                Enviar para Avaliação
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-2xl text-center shadow-3xs">
                                        <p class="text-sm font-bold text-emerald-700">
                                            <i class="fas fa-check-circle mr-2"></i> Fase totalmente aprovada e finalizada!
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="xl:col-span-7 space-y-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Histórico de Versões e Feedbacks</h3>
                                
                               @forelse($fase->submissoes as $subIndex => $sub)
    @php $versaoNum = $fase->submissoes->count() - $subIndex; @endphp
    
    <div class="border border-slate-100 bg-slate-50/40 rounded-2xl p-4 md:p-5 space-y-4 shadow-3xs">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[11px] font-black bg-slate-200 text-slate-700 px-2 py-0.5 rounded">
                    Versão #{{ $versaoNum }}
                </span>
                
                {{-- Ícone de Status de Visualização baseado no booleano 'visto' --}}
                @if($sub->visto)
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-green-50 text-green-600 px-2 py-0.5 rounded border border-green-200/50">
                        <i class="fas fa-check-double text-[9px]"></i> Visualizado
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-amber-50 text-amber-600 px-2 py-0.5 rounded border border-amber-200/50 animate-pulse">
                        <i class="fas fa-clock text-[9px]"></i> Não Visualizado
                    </span>
                @endif

                <span class="text-xs text-slate-400 ml-2 font-medium">
                    {{ $sub->created_at->translatedFormat('d/m/Y \à\s H:i') }}
                </span>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ asset('storage/' . $sub->arquivo) }}" target="_blank"
                   class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-lg text-xs font-bold transition-all">
                    <i class="fas fa-eye mr-1.5"></i> Ver Doc
                </a>

                @if(!$sub->avaliacao)
                  <div x-data="{ open: false }" class="inline">
    <button type="button" @click="open = true" class="p-1.5 bg-red-50 text-red-500 hover:bg-red-600 hover:text-white rounded-lg transition-all"><i class="fas fa-trash-alt text-xs"></i></button>
    
    {{-- Modal Direto --}}
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/45 backdrop-blur-xs" x-cloak>
        <div class="bg-white border rounded-xl max-w-xs w-full p-4 text-left shadow-xl" @click.away="open = false">
            <p class="text-xs font-bold text-slate-800">Apagar esta versão do histórico permanentemente?</p>
            <div class="mt-4 flex justify-end gap-2 text-[10px] font-bold uppercase">
                <button type="button" @click="open = false" class="px-3 py-1.5 border rounded-lg text-slate-500 hover:bg-slate-50">Cancelar</button>
                <form  action="{{ route('estudante.acompanhamentos.destroySubmissao', $sub->id) }}" method="POST" class="inline" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">@csrf @method('DELETE')<button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700">Apagar</button></form>
            </div>
        </div>
    </div>
</div>
                @endif
            </div>
        </div>

        <div class="text-sm">
            <span class="text-[10px] font-bold uppercase text-blue-500 block mb-0.5">Descrição do Aluno:</span>
            <p class="text-slate-700 italic">"{{ $sub->descricao_estudante }}"</p>
        </div>

        <div class="pt-1">
            @if($sub->avaliacao)
                <div class="bg-slate-900 text-white p-4 rounded-xl space-y-2 shadow-xs">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-wide text-blue-400 flex items-center gap-1">
                            <i class="fas fa-comment-alt"></i> Retorno do Orientador:
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">
                            {{ ucfirst($sub->avaliacao->status ?? 'Avaliado') }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-200 font-medium italic leading-relaxed">
                        "{{ $sub->avaliacao->descricao ?? $sub->avaliacao->nota_avaliacao ?? 'Sem texto de parecer cadastrado.' }}"
                    </p>
                </div>
            @else
                <div class="bg-amber-50/60 border border-dashed border-amber-200 p-3 rounded-xl text-center">
                    <p class="text-xs text-amber-600 font-semibold">
                        <i class="fas fa-hourglass-half mr-1"></i> Aguardando retorno ou nota do orientador nesta versão.
                    </p>
                </div>
            @endif
        </div>

    </div>
@empty
    {{-- Bloco disparado caso a coleção de submissões esteja vazia --}}
    <div class="border-2 border-dashed border-slate-100 rounded-2xl p-8 text-center text-slate-400">
        <i class="fas fa-folder-open text-2xl text-slate-200 mb-2 block"></i>
        <p class="text-xs font-medium">Nenhum arquivo enviado para esta fase ainda.</p>
    </div>
@endforelse
                            </div>

                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-100 p-12 rounded-[2rem] text-center text-slate-500 shadow-sm">
                    <i class="fas fa-exclamation-circle text-4xl text-slate-200 mb-3 block"></i>
                    Nenhuma fase de acompanhamento configurada pelo orientador.
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection