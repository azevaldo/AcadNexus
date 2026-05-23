 @extends('app.private.dashboard.app.templete')

@section('title', 'Avaliação de Acompanhamento')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10 font-sans" x-data="{ abaAtiva: 0 }">
    
    {{-- Cabeçalho Superior --}}
    <div class="bg-white border-2 border-gray-900 p-6 mb-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between items-start">
            <div>
                <span class="text-[10px] font-black bg-gray-900 text-white px-2 py-1 uppercase tracking-widest">Painel de Avaliação TCC</span>
                <h1 class="text-3xl font-black text-gray-900 mt-2 uppercase">{{ $acompanhamento->estudante->user->name }}</h1>
                <p class="text-sm text-gray-500 font-bold uppercase tracking-tight">Status Geral: 
                    <span class="text-blue-600">{{ strtoupper($acompanhamento->status) }}</span>
                </p>
            </div>
            <div class="flex items-center gap-6 border-l-2 border-gray-100 pl-6">
                <div class="text-right">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Mês de Referência</p>
                    {{-- Exibe apenas o Mês e Ano de Início do TCC --}}
                    <p class="text-sm font-bold text-gray-800 uppercase">
                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->translatedFormat('F / Y') }}
                    </p>
                </div>
                @if(!$acompanhamento->fases->isEmpty())
                <a href="{{ route('tutor.acompanhamento.edit', $acompanhamento->id) }}" 
                   class="flex items-center gap-2 px-5 py-3 bg-white border-2 border-gray-900 text-gray-900 text-[10px] font-black uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 active:shadow-none">
                    <i class="fas fa-calendar-alt"></i> Ajustar Cronograma
                </a>
                @endif
                @if($acompanhamento->fases->isEmpty())
                 <a href="{{ route('tutor.acompanhamento.createFases', $acompanhamento->id) }}" 
                   class="flex items-center gap-2 px-5 py-3 bg-white border-2 border-gray-900 text-gray-900 text-[10px] font-black uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 active:shadow-none">
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
                <button @click="abaAtiva = {{ $index }}" 
                    :class="abaAtiva === {{ $index }} ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-400'"
                    class="w-full flex flex-col p-4 border-2 transition-all text-left group relative overflow-hidden">
                    
                    <span class="text-[9px] font-black uppercase tracking-widest opacity-60">Mês {{ $fase->mes_inicio }} @if($fase->mes_inicio != $fase->mes_fim) a {{ $fase->mes_fim }} @endif</span>
                    <span class="text-sm font-bold truncate">{{ $fase->fase }}</span>
                    
                    {{-- Cálculo da Data Real em tempo de execução --}}
                    <span class="text-[9px] mt-1 font-medium italic opacity-80">
                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_inicio - 1)->translatedFormat('M/y') }} 
                        até 
                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('M/y') }}
                    </span>

                    <div class="mt-2 text-[8px] font-black uppercase px-2 py-0.5 rounded border inline-block w-fit
                        @if($fase->status == 'aprovado') bg-green-500/20 text-green-400 border-green-500/30 
                        @elseif($fase->status == 'pendente') bg-amber-500/20 text-amber-400 border-amber-500/30
                        @else bg-blue-500/20 text-blue-400 border-blue-500/30 @endif">
                        {{ $fase->status }}
                    </div>
                </button>
            @endforeach
        </div>

        {{-- Conteúdo Central da Avaliação --}}
        <div class="lg:col-span-3">
            @foreach($acompanhamento->fases as $index => $fase)
                <div x-show="abaAtiva === {{ $index }}" x-transition:enter="transition ease-out duration-300" class="space-y-6">
                    
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 uppercase leading-none">{{ $fase->fase }}</h2>
                                <p class="text-gray-500 text-sm mt-2 italic">Estimativa: Mês {{ $fase->mes_inicio }} ao {{ $fase->mes_fim }} do projeto.</p>
                            </div>
                            <div class="flex gap-4">
                                {{-- Bloco Mês Inicial --}}
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-center min-w-[80px]">
                                    <span class="text-[10px] font-black text-gray-400 uppercase block">Início</span>
                                    <span class="text-sm font-black text-gray-900 uppercase">
                                        {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_inicio - 1)->translatedFormat('M / Y') }}
                                    </span>
                                </div>
                                {{-- Bloco Mês Final --}}
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

                        <div class="mt-10 border-t-2 border-gray-50 pt-8">
                            <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center">
                                <i class="fas fa-file-alt mr-2 text-blue-500"></i>
                                Submissão do Estudante
                            </h4>

                            @if($fase->submissao)
                                <div class="bg-white border-2 border-gray-100 rounded-2xl p-6 mb-4 flex items-center justify-between shadow-sm">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                                            <i class="fas fa-file-pdf text-2xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-800">Versão de Entrega - Fase {{ $index + 1 }}</p>
                                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Recebido em: {{ $fase->submissao->created_at->format('d/m/Y - H:i') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $fase->submissao->arquivo) }}" target="_blank" 
                                       class="px-6 py-2 bg-gray-900 text-white text-[10px] font-black rounded-lg hover:bg-blue-600 transition-all uppercase shadow-lg shadow-gray-200">
                                        Baixar Arquivo
                                    </a>
                                </div>

                                {{-- Formulário de Avaliação --}}
                                <form action="{{ route('tutor.fase.avaliar', $fase->id) }}" method="POST" class="mt-8 bg-slate-900 p-8 rounded-[2rem] shadow-2xl">
                                    @csrf
                                    <h5 class="text-white text-xs font-black uppercase tracking-[0.2em] mb-6 flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span> Emitir Parecer Técnico
                                    </h5>
                                    
                                    <div class="grid grid-cols-1 gap-6">
                                        <div>
                                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Resultado da Avaliação</label>
                                            <select name="status" class="w-full bg-slate-800 border-none text-white rounded-xl px-4 py-4 text-sm font-bold focus:ring-2 focus:ring-blue-500">
                                                <option value="aprovado" {{ $fase->status == 'aprovado' ? 'selected' : '' }}>✓ APROVAR FASE</option>
                                                <option value="ajustes" {{ $fase->status == 'ajustes' ? 'selected' : '' }}>⚠ SOLICITAR CORREÇÕES</option>
                                                <option value="pendente" {{ $fase->status == 'pendente' ? 'selected' : '' }}>... MANTER EM ANÁLISE</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Feedback para o Aluno</label>
                                            <textarea name="descricao" rows="4" required 
                                                class="w-full bg-slate-800 border-none text-white rounded-xl px-4 py-4 text-sm focus:ring-2 focus:ring-blue-500" 
                                                placeholder="Descreva os pontos de melhoria ou justificativa da aprovação...">{{ $fase->submissao->avaliacao->descricao ?? '' }}</textarea>
                                        </div>

                                        <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-xs rounded-2xl transition-all shadow-xl shadow-indigo-900/20">
                                            Confirmar Avaliação da Fase
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-16 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 text-gray-300 rounded-full mb-4">
                                        <i class="fas fa-clock text-2xl"></i>
                                    </div>
                                    <p class="text-gray-400 font-black uppercase text-xs tracking-widest">Aguardando envio do estudante</p>
                                    <p class="text-[10px] text-gray-300 mt-1">O prazo estimado é até o final de {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('F \d\e Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection