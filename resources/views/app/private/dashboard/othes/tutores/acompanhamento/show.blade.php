@extends('app.private.dashboard.app.templete')

@section('title', 'Avaliação de Acompanhamento')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] { display: none !important; }
</style>

<div class="max-w-7xl mx-auto px-4 py-10 font-sans" x-data="{ abaAtiva: 0 }" x-cloak>
    
 {{-- Cabeçalho Superior --}}
<div class="bg-white border-2 border-gray-900 p-6 mb-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,0.05)]">
    <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
        
        {{-- Bloco Esquerdo: Voltar + Títulos --}}
        <div class="flex items-start gap-4">
            {{-- Botão Voltar no Estilo Brutalista Coerente --}}
            <a href="{{ route('tutor.estudantes.index') }}" 
               class="mt-1 flex items-center justify-center w-12 h-12 bg-white border-2 border-gray-900 text-gray-900 text-sm font-black transition-all shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:bg-gray-900 hover:text-white active:translate-y-0.5 active:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] shrink-0"
               title="Voltar para Estudantes">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div>
                <span class="text-[10px] font-black bg-gray-900 text-white px-2 py-1 uppercase tracking-widest inline-block">
                    Painel de Avaliação TCC
                </span>
                <h1 class="text-3xl font-black text-gray-900 mt-2 uppercase leading-tight">
                    {{ $acompanhamento->estudante->user->name }}
                </h1>
                <p class="text-sm text-gray-500 font-bold uppercase tracking-tight mt-1">
                    Status Geral: 
                    <span class="text-blue-600">{{ strtoupper($acompanhamento->status) }}</span>
                </p>
            </div>
        </div>

        {{-- Bloco Direito: Estatística + Botões de Ação --}}
        <div class="flex flex-wrap items-center gap-4 lg:border-l-2 border-gray-100 lg:pl-6 w-full lg:w-auto">
            <div class="text-left lg:text-right min-w-[120px]">
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Mês de Referência</p>
                <p class="text-sm font-bold text-gray-800 uppercase mt-0.5">
                    {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->translatedFormat('F / Y') }}
                </p>
            </div>

            @if(!$acompanhamento->fases->isEmpty())
                <a href="{{ route('tutor.acompanhamento.edit', $acompanhamento->id) }}" 
                   class="flex items-center gap-2 px-5 py-3 bg-white border-2 border-gray-900 text-gray-900 text-[10px] font-black uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    <i class="fas fa-calendar-alt"></i> Ajustar Cronograma
                </a>
            @else
                <a href="{{ route('tutor.acompanhamento.createFases', $acompanhamento->id) }}" 
                   class="flex items-center gap-2 px-5 py-3 bg-white border-2 border-gray-900 text-gray-900 text-[10px] font-black uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                    <i class="fas fa-calendar-alt"></i> Fazer o Cronograma
                </a>
            @endif
        </div>

    </div>
</div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- Menu Lateral de Fases --}}
        <div class="lg:col-span-1 space-y-2">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Fluxo de Meses</h3>
            @foreach($acompanhamento->fases as $index => $fase)
                <button type="button" @click="abaAtiva = {{ $index }}" 
                    :class="abaAtiva === {{ $index }} ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-400'"
                    class="w-full flex flex-col p-4 border-2 transition-all text-left group relative overflow-hidden">
                    
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Mês {{ $fase->mes_inicio }} @if($fase->mes_inicio != $fase->mes_fim) a {{ $fase->mes_fim }} @endif</span>
                    <span class="text-sm font-bold truncate">{{ $fase->fase }}</span>
                    
                    <span class="text-[9px] mt-1 font-medium italic opacity-80">
                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_inicio - 1)->translatedFormat('M/y') }} 
                        até 
                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('M/y') }}
                    </span>

                    <div class="mt-2 text-[8px] font-black uppercase px-2 py-0.5 rounded border inline-block w-fit
                        @if($fase->status == 'aprovado') bg-green-500/20 text-green-600 border-green-500/30 
                        @elseif($fase->status == 'ajustes') bg-red-500/20 text-red-600 border-red-500/30
                        @else bg-amber-500/20 text-amber-600 border-amber-500/30 @endif">
                        {{ $fase->status ?? 'Pendente' }}
                    </div>
                </button>
            @endforeach
        </div>

        {{-- Conteúdo Central da Avaliação --}}
        <div class="lg:col-span-3">
            @foreach($acompanhamento->fases as $index => $fase)
                <div x-show="abaAtiva === {{ $index }}" x-transition:enter="transition ease-out duration-300" class="space-y-6" x-cloak>
                    
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 uppercase leading-none">{{ $fase->fase }}</h2>
                                <p class="text-gray-500 text-sm mt-2 italic">Estimativa: Mês {{ $fase->mes_inicio }} ao {{ $fase->mes_fim }} do projeto.</p>
                            </div>
                            <div class="flex gap-4">
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-center min-w-[80px]">
                                    <span class="text-[10px] font-black text-gray-400 uppercase block">Início</span>
                                    <span class="text-sm font-black text-gray-900 uppercase">
                                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_inicio - 1)->translatedFormat('M / Y') }}
                                    </span>
                                </div>
                                <div class="bg-red-50 p-3 rounded-xl border border-red-100 text-center min-w-[80px]">
                                    <span class="text-[10px] font-black text-red-400 uppercase block">Entrega</span>
                                    <span class="text-sm font-black text-red-600 uppercase">
                                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('M / Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-xl mb-8 border-l-4 border-gray-300">
                            <p class="text-sm text-gray-600">{{ $fase->descricao ?? 'Nenhuma instrução adicional.' }}</p>
                        </div>

                        <div class="mt-10 border-t-2 border-gray-50 pt-8 space-y-8">
                            <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-2 flex items-center">
                                <i class="fas fa-history mr-2 text-blue-500"></i> Histórico de Envios do Aluno
                            </h4>

                            @forelse($fase->submissoes as $subIndex => $sub)
                                @php $versaoNum = $fase->submissoes->count() - $subIndex; @endphp
                                
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-4">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 pb-3 border-b border-slate-200/60">
                                        <div>
                                            <span class="text-[11px] font-black bg-gray-200 text-gray-700 px-2 py-0.5 rounded">
                                                Versão #{{ $versaoNum }}
                                            </span>
                                            <span class="text-xs text-gray-400 ml-2 font-bold uppercase">
                                                Enviado: {{ $sub->created_at->format('d/m/Y - H:i') }}
                                            </span>
                                        </div>
                                        <a href="{{ asset('storage/' . $sub->arquivo) }}" target="_blank" 
                                           class="px-4 py-2 bg-gray-900 text-white text-[10px] font-black rounded-lg hover:bg-blue-600 transition-all uppercase shadow-md">
                                            <i class="fas fa-file-download mr-1"></i> Analisar Documento
                                        </a>
                                    </div>

                                    <div class="text-sm bg-white p-3 rounded-xl border border-slate-100">
                                        <span class="text-[9px] font-black uppercase text-blue-500 block mb-1">Comentário do Aluno:</span>
                                        <p class="text-gray-700 italic">"{{ $sub->descricao_estudante ?? 'Sem comentários informados.' }}"</p>
                                    </div>

                                    {{-- Bloco de Parecer Técnico --}}
                                    <div class="pt-2">
                                        @if($sub->avaliacao)
                                            <div class="bg-slate-900 text-white p-5 rounded-xl space-y-3 relative">
                                                <div class="flex justify-between items-center border-b border-slate-800 pb-2">
                                                    <span class="text-[10px] font-black uppercase tracking-wide text-blue-400">
                                                        <i class="fas fa-comment-dots mr-1"></i> Feedback Registrado:
                                                    </span>
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-[10px] font-black px-2 py-0.5 rounded 
                                                            @if($sub->avaliacao->decisao == 'aprovado') bg-green-500/20 text-green-300 border-green-500/30 
                                                            @else bg-red-500/20 text-red-300 border-red-500/30 @endif">
                                                            {{ strtoupper($sub->avaliacao->decisao) }}
                                                        </span>
                                                        
                                                        {{-- Botão Eliminar Avaliação --}}
                                                       <div x-data="{ open: false }" class="inline">
    <button type="button" @click="open = true" class="p-1 text-red-400 hover:text-red-500 transition-colors" title="Deletar Avaliação"><i class="fas fa-trash-alt text-xs"></i></button>
    
    {{-- Modal Direto --}}
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/45 backdrop-blur-xs" x-cloak>
        <div class="bg-white border rounded-xl max-w-xs w-full p-4 text-left shadow-xl" @click.away="open = false">
            <p class="text-xs font-bold text-slate-800">Deseja realmente remover esta avaliação? O status retornará para análise.</p>
            <div class="mt-4 flex justify-end gap-2 text-[10px] font-bold uppercase">
                <button type="button" @click="open = false" class="px-3 py-1.5 border rounded-lg text-slate-500 hover:bg-slate-50">Voltar</button>
                <form action="{{ route('tutor.avaliacao.destroy', $sub->avaliacao->id) }}" method="POST" class="inline">@csrf @method('DELETE')<button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700">Excluir</button></form>
            </div>
        </div>
    </div>
</div>
                                                    </div>
                                                </div>
                                                <p class="text-sm text-slate-200 italic leading-relaxed">
                                                    "{{ $sub->avaliacao->descricao }}"
                                                </p>
                                            </div>
                                        @else
                                            {{-- Formulário de Avaliação --}}
                                            <form action="{{ route('tutor.fase.avaliar', $sub->id) }}" method="POST" class="bg-gray-100 border border-gray-200 p-5 rounded-xl space-y-4">
                                                @csrf
                                                <h5 class="text-gray-700 text-[10px] font-black uppercase tracking-wider flex items-center">
                                                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full mr-2"></span> Avaliar Versão #{{ $versaoNum }}
                                                </h5>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                    <div class="md:col-span-1">
                                                        <label class="text-[9px] font-black text-gray-500 uppercase mb-1 block">Resultado</label>
                                                        <select name="status" class="w-full bg-white border-2 border-gray-200 text-gray-800 rounded-lg px-3 py-2 text-xs font-bold focus:border-indigo-500 focus:outline-none">
                                                            <option value="aprovado">✓ APROVAR</option>
                                                            <option value="ajustes">⚠ PEDIR AJUSTES</option>
                                                        </select>
                                                    </div>
                                                    <div class="md:col-span-3">
                                                        <label class="text-[9px] font-black text-gray-500 uppercase mb-1 block">Instruções de Feedback</label>
                                                        <textarea name="descricao" rows="2" required 
                                                            class="w-full bg-white border-2 border-gray-200 text-gray-800 rounded-lg px-3 py-1.5 text-xs focus:border-indigo-500 focus:outline-none" 
                                                            placeholder="Descreva detalhadamente o parecer técnico..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end">
                                                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase text-[10px] rounded-lg transition-all shadow-md">
                                                        Confirmar Parecer
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 text-gray-300 rounded-full mb-3">
                                        <i class="fas fa-clock text-xl"></i>
                                    </div>
                                    <p class="text-gray-400 font-black uppercase text-xs tracking-widest">Aguardando envio do estudante</p>
                                    <p class="text-[10px] text-gray-300 mt-1">O prazo estimado é até o final de {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('F \d\e Y') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection