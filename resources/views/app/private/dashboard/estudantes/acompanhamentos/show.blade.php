 @extends('app.private.dashboard.app..templete')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="max-w-7xl mx-auto px-4 py-10 font-sans" x-data="{ abaAtiva: 0 }" x-cloak>
    
    <div class="bg-white border-4 border-gray-900 p-8 mb-10 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)]">
        <h1 class="text-4xl font-black text-gray-900 uppercase italic tracking-tighter">Progresso do TCC</h1>
        <p class="text-gray-500 font-bold uppercase text-xs tracking-widest mt-2">
            Orientador: {{ $acompanhamento->tutor->professor->user->name }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-4 space-y-3">
            @foreach($acompanhamento->fases as $index => $fase)
                <button @click="abaAtiva = {{ $index }}" 
                    :class="abaAtiva === {{ $index }} ? 'border-gray-900 bg-gray-900 text-white shadow-[6px_6px_0px_0px_rgba(37,99,235,1)] translate-x-2' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-400'"
                    class="w-full p-5 border-4 transition-all text-left outline-none relative overflow-hidden group">
                    
                    <span class="text-[9px] font-black uppercase opacity-70">Fase {{ $index + 1 }}</span>
                    <p class="text-sm font-black uppercase mt-1">{{ $fase->fase }}</p>

                    <div class="mt-2 flex items-center gap-2">
                        <span @class([
                            'text-[8px] font-black uppercase px-2 py-0.5 border-2',
                            'bg-green-500 text-white border-green-700' => $fase->status == 'aprovado',
                            'bg-amber-400 text-black border-amber-600' => $fase->status == 'pendente' || $fase->status == 'submetido',
                            'bg-red-500 text-white border-red-700' => $fase->status == 'ajustes',
                        ])>
                            {{ $fase->status }}
                        </span>
                    </div>
                </button>
            @endforeach
        </div>

        <div class="lg:col-span-8">
            @foreach($acompanhamento->fases as $index => $fase)
                <div x-show="abaAtiva === {{ $index }}" x-transition:enter="transition ease-out duration-300" class="space-y-6">
                    
                    <div class="bg-white border-4 border-gray-900 p-8 shadow-[12px_12px_0px_0px_rgba(0,0,0,0.05)]">
                        
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <h2 class="text-4xl font-black text-gray-900 uppercase italic leading-none">{{ $fase->fase }}</h2>
                                <p class="mt-2 text-xs font-black uppercase tracking-widest {{ $fase->status == 'aprovado' ? 'text-green-600' : 'text-blue-600' }}">
                                    Status: {{ $fase->status }}
                                </p>
                            </div>
                            <div class="bg-red-50 border-2 border-red-900 p-2 text-center text-red-900 font-black text-xs">
                                PRAZO: {{ \Carbon\Carbon::parse($fase->fim)->format('d/m/Y') }}
                            </div>
                        </div>

                        @if($fase->submissao && $fase->submissao->avaliacao)
                            <div class="mb-10 bg-gray-900 p-6 shadow-[8px_8px_0px_0px_rgba(37,99,235,1)]">
                                <h4 class="text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"></path></svg>
                                    Parecer do Orientador
                                </h4>
                                <div class="text-white text-sm font-bold leading-relaxed italic">
                                    "{{ $fase->submissao->avaliacao->descricao }}"
                                </div>
                                <div class="mt-4 flex items-center gap-2">
                                    <span class="text-[9px] font-black uppercase px-2 py-1 {{ $fase->submissao->avaliacao->decisao == 'aprovado' ? 'bg-green-600' : 'bg-red-600' }} text-white">
                                        Decisão: {{ strtoupper($fase->submissao->avaliacao->decisao) }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($fase->submissao)
                            <div class="bg-white border-4 border-gray-900 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] mb-10">
                                <div class="p-6 border-b-4 border-gray-900 bg-gray-50 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-white border-2 border-gray-900">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black text-gray-400 uppercase">Arquivo Atual</p>
                                            <p class="text-sm font-black truncate max-w-xs">{{ $fase->submissao->arquivo }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <a href="{{ asset('storage/' . $fase->submissao->arquivo) }}" target="_blank" class="px-4 py-2 bg-gray-900 text-white text-[10px] font-black uppercase hover:bg-blue-600 transition">Ver</a>
                                        
                                        @if($fase->status !== 'aprovado')
                                        <form action="{{ route('estudante.submissao.destroy', $fase->submissao->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar esta submissão?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-[10px] font-black uppercase hover:bg-red-800 transition">Apagar</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-6">
                                    <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Minha Descrição:</p>
                                    <p class="text-sm font-bold text-gray-700 italic">"{{ $fase->submissao->descricao_estudante }}"</p>
                                </div>
                            </div>
                        @endif

                        @if($fase->status !== 'aprovado')
                            <div class="border-t-4 border-gray-100 pt-8">
                                <h3 class="text-xs font-black uppercase mb-6">{{ $fase->submissao ? 'Substituir por nova versão' : 'Enviar Trabalho' }}</h3>
                                
                                <form action="{{ route('estudante.submissao.store', $fase->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    <div class="relative border-4 border-dashed border-gray-300 p-10 text-center hover:border-gray-900 transition group bg-white">
                                        <input type="file" name="arquivo" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <p class="text-sm font-black text-gray-900 uppercase">Arraste ou clique para selecionar o novo PDF</p>
                                    </div>

                                    <div class="bg-white border-4 border-gray-900 p-4">
                                        <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">O que mudou nesta versão?</label>
                                        <textarea name="descricao_estudante" rows="3" required class="w-full border-none focus:ring-0 text-sm font-bold" placeholder="Descreva brevemente..."></textarea>
                                    </div>

                                    <button type="submit" class="w-full py-5 bg-blue-600 text-white font-black uppercase tracking-[0.2em] shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] hover:bg-gray-900 transition-all active:translate-y-1 active:shadow-none">
                                        {{ $fase->submissao ? 'Confirmar Substituição' : 'Enviar para Avaliação' }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-green-500 text-white p-6 border-4 border-gray-900 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex items-center gap-4">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                <div>
                                    <p class="text-xl font-black uppercase">Etapa Aprovada!</p>
                                    <p class="text-sm font-bold opacity-80 uppercase">Parabéns! O orientador aprovou esta fase do seu TCC.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection