 @extends('app.private.dashboard.app..templete')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="max-w-7xl mx-auto px-4 py-10 font-sans" x-data="{ abaAtiva: 0 }" x-cloak>
    
    {{-- Cabeçalho --}}
    <div class="bg-white border-4 border-gray-900 p-8 mb-10 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-4xl font-black text-gray-900 uppercase italic tracking-tighter">Meu Progresso TCC</h1>
                <p class="text-gray-500 font-bold uppercase text-xs tracking-widest mt-2">
                    Orientador: <span class="text-gray-900">{{ $acompanhamento->tutor->user->name }}</span>
                </p>
            </div>
            <div class="text-right hidden md:block">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Início do Projeto</p>
                <p class="text-lg font-black text-gray-900 uppercase">
                    {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->translatedFormat('F / Y') }}
                </p>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Menu Lateral --}}
        <div class="lg:col-span-4 space-y-3">
            @foreach($acompanhamento->fases as $index => $fase)
                <button @click="abaAtiva = {{ $index }}" 
                    :class="abaAtiva === {{ $index }} ? 'border-gray-900 bg-gray-900 text-white shadow-[6px_6px_0px_0px_rgba(37,99,235,1)] translate-x-2' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-400'"
                    class="w-full p-5 border-4 transition-all text-left outline-none relative overflow-hidden group">
                    
                    <div class="flex justify-between items-start">
                        <span class="text-[9px] font-black uppercase opacity-70">Cronograma: Mês {{ $fase->mes_inicio }}</span>
                        <span @class([
                            'text-[8px] font-black uppercase px-2 py-0.5 border-2',
                            'bg-green-500 text-white border-green-700' => $fase->status == 'aprovado',
                            'bg-amber-400 text-black border-amber-600' => in_array($fase->status, ['pendente', 'submetido']),
                            'bg-red-500 text-white border-red-700' => $fase->status == 'ajustes',
                        ])>
                            {{ $fase->status }}
                        </span>
                    </div>

                    <p class="text-sm font-black uppercase mt-1">{{ $fase->fase }}</p>
                    
                    <p class="text-[10px] mt-2 font-bold italic opacity-60">
                        {{-- Lógica direta aplicada aqui --}}
                        Prazo: {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('M / Y') }}
                    </p>
                </button>
            @endforeach
        </div>

        {{-- Conteúdo Principal --}}
        <div class="lg:col-span-8">
            @foreach($acompanhamento->fases as $index => $fase)
                <div x-show="abaAtiva === {{ $index }}" x-transition:enter="transition ease-out duration-300" class="space-y-6">
                    
                    <div class="bg-white border-4 border-gray-900 p-8 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.05)]">
                        
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-8">
                            <div>
                                <h2 class="text-4xl font-black text-gray-900 uppercase italic leading-none">{{ $fase->fase }}</h2>
                                <div class="flex gap-4 mt-4">
                                    {{-- Bloco Mês Inicial --}}
                                    <div class="bg-gray-50 p-2 border-2 border-gray-900 text-center min-w-[100px]">
                                        <span class="text-[9px] font-black text-gray-400 uppercase block">Início</span>
                                        <span class="text-xs font-black text-gray-900 uppercase">
                                            {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_inicio - 1)->translatedFormat('M / Y') }}
                                        </span>
                                    </div>
                                    {{-- Bloco Mês Final --}}
                                    <div class="bg-red-50 p-2 border-2 border-red-900 text-center min-w-[100px] shadow-[4px_4px_0px_0px_rgba(153,27,27,1)]">
                                        <span class="text-[9px] font-black text-red-400 uppercase block">Entrega</span>
                                        <span class="text-xs font-black text-red-600 uppercase">
                                            {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('M / Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 p-4 bg-gray-50 border-l-8 border-gray-900 text-sm font-bold text-gray-600">
                            {{ $fase->descricao ?? 'Sem instruções específicas.' }}
                        </div>

                        @if($fase->submissao && $fase->submissao->avaliacao)
                            <div class="mb-10 bg-gray-900 p-6 shadow-[8px_8px_0px_0px_rgba(37,99,235,1)] text-white">
                                <h4 class="text-blue-400 text-[10px] font-black uppercase mb-3 italic">Retorno do Orientador:</h4>
                                <div class="text-sm font-bold leading-relaxed italic">
                                    "{{ $fase->submissao->avaliacao->descricao }}"
                                </div>
                            </div>
                        @endif

                        @if($fase->submissao)
                            <div class="bg-white border-4 border-gray-900 mb-10">
                                <div class="p-6 bg-gray-50 flex flex-col md:flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="p-2 bg-white border-2 border-gray-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-sm font-black truncate max-w-xs text-blue-600">{{ $fase->submissao->arquivo }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ asset('storage/' . $fase->submissao->arquivo) }}" target="_blank" class="px-4 py-2 bg-gray-900 text-white text-[10px] font-black uppercase">Ver</a>
                                        @if($fase->status !== 'aprovado')
                                        <form action="{{ route('estudante.submissao.destroy', $fase->submissao->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="px-4 py-2 bg-red-600 text-white text-[10px] font-black uppercase">Apagar</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($fase->status !== 'aprovado')
                            <div class="border-t-4 border-gray-100 pt-8">
                                <form action="{{ route('estudante.submissao.store', $fase->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    <div class="relative border-4 border-dashed border-gray-300 p-10 text-center hover:border-gray-900 transition bg-white">
                                        <input type="file" name="arquivo" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <p class="text-sm font-black text-gray-900 uppercase italic">Selecione o arquivo para o prazo de {{ \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('F / Y') }}</p>
                                    </div>
                                    <div class="bg-white border-4 border-gray-900 p-4">
                                        <textarea name="descricao_estudante" rows="2" required class="w-full border-none focus:ring-0 text-sm font-bold" placeholder="Comentário sobre o envio..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full py-5 bg-blue-600 text-white font-black uppercase shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:bg-gray-900 transition-all">
                                        Enviar para Avaliação
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-green-500 text-white p-6 border-4 border-gray-900 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex items-center gap-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                <p class="text-xl font-black uppercase">Fase Aprovada!</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection