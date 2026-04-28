 @extends('app.private.dashboard.app..templete')

@section('title', 'Avaliação de Acompanhamento')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10 font-sans" x-data="{ abaAtiva: 0 }">
    
    <div class="bg-white border-2 border-gray-900 p-6 mb-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between items-start">
            <div>
                <span class="text-[10px] font-black bg-gray-900 text-white px-2 py-1 uppercase tracking-widest">Painel de Controle TCC</span>
                <h1 class="text-3xl font-black text-gray-900 mt-2 uppercase">{{ $acompanhamento->estudante->user->name }}</h1>
                <p class="text-sm text-gray-500 font-bold uppercase tracking-tight">Status Geral: 
                    <span class="text-blue-600">{{ strtoupper($acompanhamento->status) }}</span>
                </p>
            </div>
           <div class="flex items-center gap-6 border-l-2 border-gray-100 pl-6">
                <a href="{{ route('tutor.acompanhamento.edit', $acompanhamento->id) }}" 
                   class="flex items-center gap-2 px-5 py-3 bg-white border-2 border-gray-900 text-gray-900 text-[10px] font-black uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-y-1 active:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar Cronograma
                </a>

                <div class="text-right hidden sm:block">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Iniciado em</p>
                    <p class="text-sm font-bold text-gray-800">{{ $acompanhamento->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <div class="lg:col-span-1 space-y-2">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Cronograma de Fases</h3>
            @foreach($acompanhamento->fases as $index => $fase)
                <button @click="abaAtiva = {{ $index }}" 
                    :class="abaAtiva === {{ $index }} ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-400'"
                    class="w-full flex flex-col p-4 border-2 transition-all text-left group">
                    <span class="text-[9px] font-black uppercase tracking-widest">Fase {{ $index + 1 }}</span>
                    <span class="text-sm font-bold truncate">{{ $fase->fase }}</span>
                    
                    {{-- Data Adicionada no Botão Lateral --}}
                    <span class="text-[9px] mt-1 font-medium opacity-70">
                        {{ \Carbon\Carbon::parse($fase->inicio)->format('d/m') }} até {{ \Carbon\Carbon::parse($fase->fim)->format('d/m/Y') }}
                    </span>

                    <div class="mt-2 text-[8px] font-black uppercase px-2 py-0.5 rounded border inline-block"
                        @if($fase->status == 'aprovado') class="bg-green-100 text-green-700 border-green-200"
                        @elseif($fase->status == 'pendente') class="bg-amber-100 text-amber-700 border-amber-200"
                        @else class="bg-blue-100 text-blue-700 border-blue-200" @endif>
                        {{ $fase->status }}
                    </div>
                </button>
            @endforeach
        </div>

        <div class="lg:col-span-3">
            @foreach($acompanhamento->fases as $index => $fase)
                <div x-show="abaAtiva === {{ $index }}" x-transition:enter="transition ease-out duration-300" class="space-y-6">
                    
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 uppercase leading-none">{{ $fase->fase }}</h2>
                                <p class="text-gray-500 text-sm mt-2">{{ $fase->descricao ?? 'Sem descrição detalhada para esta fase.' }}</p>
                            </div>
                            <div class="text-right flex gap-4">
                                {{-- Data de Início --}}
                                <div>
                                    <span class="text-[10px] font-black text-gray-400 uppercase block">Início</span>
                                    <span class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($fase->inicio)->format('d/m/Y') }}</span>
                                </div>
                                {{-- Data de Fim --}}
                                <div>
                                    <span class="text-[10px] font-black text-gray-400 uppercase block">Prazo Final</span>
                                    <span class="text-sm font-bold text-red-600">{{ \Carbon\Carbon::parse($fase->fim)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 border-t-2 border-gray-50 pt-8">
                            <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Documentos Submetidos
                            </h4>

                            @if($fase->submissao)
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-4 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-white border border-gray-200 rounded-lg text-blue-600">
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">Trabalho_Fase_{{ $fase->id }}.pdf</p>
                                            <p class="text-[10px] text-gray-400 uppercase font-bold">Enviado em: {{ $fase->submissao->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $fase->submissao->arquivo) }}" target="_blank" class="px-4 py-2 bg-white border border-gray-300 rounded text-xs font-black hover:bg-gray-900 hover:text-white transition-all uppercase">Visualizar</a>
                                </div>

                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 mb-8">
                                    <h5 class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">Comentário do Estudante:</h5>
                                    <p class="text-sm text-gray-700 italic">"{{ $fase->submissao->descricao_estudante }}"</p>
                                </div>

                                <form action="{{ route('tutor.fase.avaliar', $fase->id) }}" method="POST" class="mt-8 bg-gray-900 p-8 rounded-2xl shadow-xl">
                                    @csrf
                                    <h5 class="text-white text-xs font-black uppercase tracking-[0.2em] mb-6">Parecer do Tutor</h5>
                                    
                                    <div class="grid grid-cols-1 gap-6">
                                        <div>
                                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Decisão Final para esta Fase</label>
                                            <select name="status" class="w-full bg-gray-800 border-none text-white rounded-lg px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-blue-500">
                                                <option value="aprovado" {{ $fase->status == 'aprovado' ? 'selected' : '' }}>APROVAR FASE</option>
                                                <option value="ajustes" {{ $fase->status == 'ajustes' ? 'selected' : '' }}>SOLICITAR AJUSTES</option>
                                                <option value="pendente" {{ $fase->status == 'pendente' ? 'selected' : '' }}>MANTER EM ANÁLISE</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Feedback Técnico</label>
                                            <textarea name="descricao" rows="4" required class="w-full bg-gray-800 border-none text-white rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Escreva aqui as correções necessárias ou elogios...">{{ $fase->submissao->avaliacao->descricao ?? '' }}</textarea>
                                        </div>

                                        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-black uppercase tracking-widest text-xs rounded-xl transition-all shadow-lg">
                                            Publicar Avaliação da Fase
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-12 text-center">
                                    <p class="text-gray-400 font-bold italic text-sm">O estudante ainda não realizou submissões para esta fase.</p>
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