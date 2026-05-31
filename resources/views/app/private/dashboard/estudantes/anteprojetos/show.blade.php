 @extends('app.private.dashboard.app.templete')

@section('title', 'Detalhes do Anteprojeto')

@section('content')

{{-- Adicionado no x-data: openDeleteModal e deleteRoute --}}
<div class="max-w-6xl mx-auto px-6 py-10" 
     x-data="{ 
        openModal: false, 
        modalTitle: '', 
        modalContent: '', 
        openDeleteModal: false, 
        deleteRoute: '',
        openEditTitleModal: false 
     }">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('estudante.anteprojetos.meuAnteprojeto', $campanha->id) }}" class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 hover:text-blue-600 transition shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
           <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">{{ $anteprojeto->titulo }}</h1>
                    {{-- BOTÃO PARA ABRIR EDIÇÃO --}}
                    <button @click="openEditTitleModal = true" class="text-slate-300 hover:text-blue-600 transition">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100 text-right">
                <span class="text-[10px] text-slate-400 font-black uppercase block tracking-widest">Estado Geral</span>
                @php
                    $statusColors = [
                        'aprovado' => 'text-emerald-600',
                        'rejeitado' => 'text-red-600',
                        'melhorias' => 'text-amber-600',
                        'submetido' => 'text-blue-600'
                    ];
                @endphp
                <span class="text-sm font-black uppercase {{ $statusColors[$anteprojeto->status] ?? 'text-slate-600' }}">
                    {{ $anteprojeto->status }}
                </span>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3">
             


             @if($anteprojeto->status=='aprovado')
                    <div class="alert">Aprovado Com Sucesso</div>
                @else
                
                @if($campanha->prazo_melhorias)
                 
                    @if($fase2==true)
                        @if($anteprojeto->status == 'melhorias')
                            <a href="{{ route('estudante.anteprojetos.nova_versao', $anteprojeto->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-amber-200 transition-all flex items-center">
                                <i class="fas fa-sync-alt mr-2"></i> Enviar Nova Versão
                            </a>
                        @elseif($anteprojeto->status =='rejeitado' && $totalAnteprojetos==1  && $totalVersoes==1)
                            <a href="{{ route('estudante.anteprojetos.create', $campanha->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-amber-200 transition-all flex items-center">
                                <i class="fas fa-sync-alt mr-2"></i>    Novo Anteprojeto
                            </a>
                            @elseif($anteprojeto->status =='rejeitado' && $totalVersoes>1)

                            <div class="alert">Campanha Finalizada, Espere a proxima</div>
                        
                        @endif
                    @else
                    <div class="alert">Prazo Para Melhorias Esgotado</div>
                    @endif
                @endif

            @endif



                   
             
        </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center">
                        <i class="fas fa-align-left mr-3 text-blue-500"></i> Resumo da Proposta
                    </h2>
                    <span class="bg-blue-50 text-blue-600 text-[10px] font-black uppercase px-2 py-1 rounded">Versão Atual</span>
                </div>
                <div class="bg-slate-50 p-6 rounded-2xl text-slate-600 leading-relaxed border border-slate-100 italic">
                    "{{ $anteprojeto->versoes->last()->descricao ?? 'Sem descrição' }}"
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50">
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center">
                        <i class="fas fa-history mr-3 text-indigo-500"></i> Linha do Tempo de Versões
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <thead>
    <tr class="bg-slate-50/50">
        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Versão</th>
        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Data de Envio</th>
        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vista?</th> 

        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Ações</th>
    </tr>
</thead>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                           @foreach($anteprojeto->versoes()->orderBy('numero_versao', 'desc')->get() as $v)
<tr class="hover:bg-slate-50/30 transition-colors group">
    <td class="px-8 py-5">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-xs">
                V{{ $v->numero_versao }}
            </span>
            @if($loop->first)
                <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-tighter bg-emerald-50 px-1.5 py-0.5 rounded">Atual</span>
            @endif
        </div>
    </td>
    <td class="px-8 py-5 text-sm text-slate-600 font-medium">
        {{ $v->created_at->format('d/m/Y') }} 
        <span class="text-slate-400 text-xs ml-1 font-normal">{{ $v->created_at->format('H:i') }}</span>
    </td>

    {{-- NOVA COLUNA DE STATUS DA VERSÃO --}}
    <td class="px-8 py-5">
        @php
            $vStatusColors = [
                'aprovado'  => 'bg-emerald-50 text-emerald-600',
                'rejeitado' => 'bg-red-50 text-red-600',
                'melhorias' => 'bg-amber-50 text-amber-600',
                'submetido' => 'bg-blue-50 text-blue-600'
            ];
            $vStatusClass = $vStatusColors[$v->status] ?? 'bg-slate-50 text-slate-600';
        @endphp
        <span class="text-[10px] font-black uppercase px-2 py-1 rounded-md {{ $vStatusClass }}">
            {{ $v->status }}
        </span>
    </td>
<td class="px-8 py-5">
        @php
            $vStatusColors = [
                '1'  => 'bg-emerald-50 text-emerald-600',
                '0' => 'bg-red-50 text-red-600',
                    ];
            $vStatusClass = $vStatusColors[$v->lida] ?? 'bg-slate-50 text-slate-600';
        @endphp
        <span class="text-[10px] font-black uppercase px-2 py-1 rounded-md {{ $vStatusClass }}">
            {{ $v->lida?'Visto':'Não Visto' }}
        </span>
    </td>
    <td class="px-8 py-5">
        <div class="flex items-center justify-center gap-2">
            <button 
                @click="openModal = true; modalTitle = 'Descrição da Versão V{{ $v->numero_versao }}'; modalContent = '{{ addslashes($v->descricao) }}'"
                class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 transition"
                title="Ver detalhes">
                <i class="fas fa-eye text-xs"></i>
            </button>

            <a href="{{ asset('storage/'.$v->arquivo) }}" target="_blank"
               class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-blue-100 hover:text-blue-600 transition"
               title="Abrir Documento">
                <i class="fas fa-external-link-alt text-xs"></i>
            </a>
                @if(!$v->lida)
            {{-- BOTÃO ELIMINAR --}}
            <button 
                @click="openDeleteModal = true; deleteRoute = '{{ route('estudante.anteprojetos.versao.destroy', $v->id) }}'"
                class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:bg-red-100 hover:text-red-600 transition"
                title="Eliminar Versão">
                <i class="fas fa-trash text-xs"></i>
            </button>
            @endif
        </div>
    </td>
</tr>
@endforeach
</tbody>
                    </table>
                </div>
            </div>

{{-- Histórico de Avaliações Refatorado com Itens de Melhoria --}}
<div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
    <h2 class="text-sm font-black text-slate-800 uppercase mb-8 tracking-widest flex items-center">
        <i class="fas fa-comment-dots mr-3 text-amber-500"></i> Pareceres Técnicos por Versão
    </h2>

    @php
        $versoesComDecisao = $anteprojeto->versoes()
            ->has('decisao')
            ->with(['decisao.coordenador.professor.user', 'decisao.itensMelhoria'])
            ->orderBy('numero_versao', 'desc')
            ->get();
    @endphp

    @if($versoesComDecisao->count() > 0)
        <div class="space-y-8">
            @foreach($versoesComDecisao as $v)
                @php $decisao = $v->decisao; @endphp
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full border-4 border-white shadow-sm {{ $decisao->decisao == 'aprovado' ? 'bg-emerald-500' : ($decisao->decisao == 'melhorias' ? 'bg-amber-500' : 'bg-red-500') }}"></div>
                        <div class="w-px h-full bg-slate-100 mt-2"></div>
                    </div>

                    <div class="flex-1 pb-4">
                        <div class="bg-slate-50/80 p-6 rounded-2xl border border-slate-100 relative shadow-sm">
                            
                            <div class="absolute -top-3 right-6">
                                <span class="bg-slate-900 text-white text-[10px] font-black px-3 py-1 rounded-full shadow-lg uppercase">
                                    Versão {{ $v->numero_versao }}
                                </span>
                            </div>

                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-widest block mb-1 {{ $decisao->decisao == 'aprovado' ? 'text-emerald-600' : ($decisao->decisao == 'melhorias' ? 'text-amber-600' : 'text-red-600') }}">
                                        Resultado da Avaliação
                                    </span>
                                    <h4 class="text-lg font-bold text-slate-900 leading-none uppercase">
                                        {{ $decisao->decisao }}
                                    </h4>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-400 font-bold block">{{ $decisao->created_at->format('d/m/Y') }}</span>
                                    <span class="text-[10px] text-slate-300">{{ $decisao->created_at->format('H:i') }}</span>
                                </div>
                            </div>

                            {{-- MENSAGEM GERAL DO COORDENADOR --}}
                            <div class="bg-white p-4 rounded-xl border border-slate-100 text-slate-600 text-sm leading-relaxed shadow-inner italic mb-4">
                                "{{ $decisao->mensagem }}"
                            </div>

                            {{-- SEÇÃO DE MELHORIAS ESPECÍFICAS --}}
                            @if($decisao->decisao == 'melhorias' && $decisao->itensMelhoria->count() > 0)
                                <div class="mt-4">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Pontos que precisam de atenção:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($decisao->itensMelhoria as $item)
                                            <button 
                                                @click="openModal = true; 
                                                        modalTitle = 'Detalhes: {{ addslashes($item->titulo) }}'; 
                                                        modalContent = '{{ addslashes($item->descricao) }}'"
                                                class="flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-100 rounded-lg text-amber-700 hover:bg-amber-100 transition-all text-left">
                                                <i class="fas fa-info-circle text-[10px]"></i>
                                                <span class="text-[10px] font-bold uppercase tracking-tight">{{ $item->titulo }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                    <p class="text-[9px] text-slate-400 mt-2 italic">* Clique nos itens acima para ver a explicação detalhada.</p>
                                </div>
                            @endif
                            
                            <div class="mt-6 pt-4 border-t border-slate-200/50 flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-[10px]">
                                    <i class="fas fa-user-check"></i>
                                </div>
                                <a href="{{ route('professores.show', $decisao->coordenador->professor->id) }}" 
           class="inline-flex items-center justify-center ml-1 text-slate-400 hover:text-blue-600 transition-colors" 
           title="Ver Perfil do Professor">
            <i class="fas fa-info-circle text-xs"></i>
        </a>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tight">
                                    Prof. {{ $decisao->coordenador->professor->user->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Estado Vazio (Mesmo do seu código) --}}
        <div class="text-center py-12 bg-slate-50/50 rounded-[2rem] border border-dashed border-slate-200">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                <i class="fas fa-hourglass-half text-slate-300 text-xl"></i>
            </div>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Aguardando Avaliação</p>
        </div>
    @endif
</div>

        </div>

        {{-- Aside --}}
        <aside class="space-y-6">
            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500/10 rounded-full"></div>
                
                <h4 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-6">Informações Gerais</h4>
                
                <div class="space-y-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-blue-400">
                            <i class="fas fa-bullhorn text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Campanha</p>
                            <p class="text-xs font-bold text-slate-200">{{ $anteprojeto->campanha->titulo }}</p>
                        </div>
                    </div>

                     <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition">
                                <i class="fas fa-upload"></i>
                            </div>
                           <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase">Submissão</span>
                                <p class="font-bold text-slate-100">{{ ($campanha->prazo_submissao)? \Carbon\Carbon::parse($campanha->prazo_submissao)->format('d/m/Y'):'Não definido' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-amber-400 group-hover:scale-110 transition">
                                <i class="fas fa-tools"></i>
                            </div>
                             <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase">Melhorias</span>
                                <p class="font-bold text-slate-100">{{ ($campanha->prazo_melhorias)?\Carbon\Carbon::parse($campanha->prazo_melhorias)->format('d/m/Y') :'Não definido'}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-emerald-400">
                            <i class="fas fa-calendar-check text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Primeiro Envio</p>
                            <p class="text-xs font-bold text-slate-200">{{ $anteprojeto->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-amber-400">
                            <i class="fas fa-layer-group text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">Total de Versões</p>
                            <p class="text-xs font-bold text-slate-200">{{ $anteprojeto->versoes()->count() }} Submissões</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-3xl p-6 border border-blue-100">
                <p class="text-blue-900 font-bold text-xs uppercase mb-2">Ajuda técnica?</p>
                <p class="text-blue-600/80 text-[11px] leading-relaxed mb-4">
                    Se não conseguir visualizar um documento antigo, contacte o suporte do departamento acadêmico.
                </p>
                <a href="#" class="text-[10px] font-black uppercase text-blue-700 hover:underline">Manual do Aluno →</a>
            </div>
        </aside>
    </div>

    {{-- MODAL DE VISUALIZAÇÃO (Descrição) --}}
    <div x-show="openModal" 
         class="fixed inset-0 z- flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-cloak
         x-transition>
        
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden" @click.away="openModal = false">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs" x-text="modalTitle"></h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-8">
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 text-slate-600 text-sm leading-relaxed italic" x-text="modalContent"></div>
            </div>
            <div class="p-6 bg-slate-50/50 border-t border-slate-100 text-right">
                <button @click="openModal = false" class="px-6 py-2 bg-slate-900 text-white text-[10px] font-black uppercase rounded-xl hover:bg-slate-800 transition">Fechar</button>
            </div>
        </div>
    </div>


{{-- NOVO: MODAL PARA EDITAR TÍTULO --}}
    <div x-show="openEditTitleModal" 
         class="fixed inset-0 z- flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-cloak
         x-transition>
        
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden" @click.away="openEditTitleModal = false">
            <form action="{{ route('estudante.anteprojetos.update_titulo', $anteprojeto->id) }}" method="POST" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                @csrf
                @method('PATCH')
                
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Editar Título do Anteprojeto</h3>
                    <button type="button" @click="openEditTitleModal = false" class="text-slate-400 hover:text-red-500 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-8">
                    <label class="text-[10px] text-slate-400 font-black uppercase tracking-widest block mb-2">Novo Título</label>
                    <textarea 
                        name="titulo" 
                        rows="3" 
                        required
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-700 font-bold focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                    >{{ $anteprojeto->titulo }}</textarea>
                </div>

                <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex gap-3 justify-end">
                    <button type="button" @click="openEditTitleModal = false" class="px-6 py-3 text-slate-500 text-[10px] font-black uppercase rounded-xl hover:bg-slate-100 transition">Cancelar</button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white text-[10px] font-black uppercase rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">Salvar Alteração</button>
                </div>
            </form>
        </div>
    </div>

    {{-- NOVO: MODAL DE CONFIRMAÇÃO DE ELIMINAÇÃO --}}
    <div x-show="openDeleteModal" 
         class="fixed inset-0 z- flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
         x-cloak
         x-transition>
        
        <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl p-8 text-center" @click.away="openDeleteModal = false">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            
            <h3 class="text-xl font-black text-slate-900 mb-2 uppercase tracking-tight">Tens a certeza?</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-8">Esta ação irá eliminar permanentemente este ficheiro e o registo da versão. Não poderá ser desfeito.</p>

            <div class="flex gap-3">
                <button @click="openDeleteModal = false" 
                        class="flex-1 px-4 py-4 bg-slate-100 text-slate-600 text-[10px] font-black uppercase rounded-2xl hover:bg-slate-200 transition">
                    Cancelar
                </button>
                
                <form :action="deleteRoute" method="POST" class="flex-1" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-4 bg-red-600 text-white text-[10px] font-black uppercase rounded-2xl hover:bg-red-700 shadow-lg shadow-red-200 transition">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection