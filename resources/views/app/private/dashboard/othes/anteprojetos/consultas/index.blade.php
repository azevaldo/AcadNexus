@extends('app.private.dashboard.app.templete')

@section('title', 'Gestão de Anteprojetos')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8" x-data="{ openFilterModal: false }">

    {{-- HEADER E ESTATÍSTICAS --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-4 mb-2">
                    {{-- Botão Voltar Dinâmico (Sempre volta para listagem limpa se houver filtro, ou histórico) --}}
                    @if($campanha)
                        <a href="{{ route('campanhas.show', $campanha->id) }}" 
                           class="flex items-center justify-center w-12 h-12 rounded-2xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm shrink-0"
                           title="Voltar para a Campanha">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    @endif

                    {{-- Ícone da Tela --}}
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200 shrink-0">
                        <i class="fas fa-folder-tree text-xl"></i>
                    </div>
                    
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        @if($campanha)
                            Anteprojetos: <span class="text-blue-600">{{ $campanha->titulo }}</span>
                        @else
                            Painel Global de Anteprojetos
                        @endif
                    </h1>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 pl-16 mt-2">
                    <p class="text-slate-500 font-medium flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                        Status: <span class="text-slate-800 font-bold">{{ $campanha ? 'Filtrado por campanha' : 'Exibindo todos os registros' }}</span>
                    </p>
                    
                    {{-- Gatilho de Filtros e Link para Limpar --}}
                    <div class="flex flex-wrap items-center gap-2 mt-2 sm:mt-0 border-t sm:border-t-0 sm:border-l pt-2 sm:pt-0 sm:pl-3 border-slate-200">
                        <button @click="openFilterModal = true" 
                                class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-white text-[10px] font-bold uppercase rounded-xl transition shadow-sm">
                            <i class="fas fa-filter text-[9px]"></i> Configurar Filtros
                        </button>

                        @if(request()->anyFilled(['campanha_id', 'titulo', 'aluno_nome', 'status', 'versao_count', 'data_inicio']))
                            <a href="{{ route('anteprojetosAdmin.index') }}" 
                               class="text-[10px] text-red-500 hover:text-red-700 font-bold uppercase underline decoration-2 underline-offset-4 transition pl-1">
                                Limpar Consulta
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Badges Informativos de Filtros Ativos --}}
                @if(request()->anyFilled(['campanha_id', 'titulo', 'aluno_nome', 'status', 'versao_count', 'data_inicio']))
                    <div class="flex flex-wrap gap-2 pl-16 mt-3">
                        @if($campanha)
                            <span class="bg-blue-50 text-blue-700 text-[9px] font-bold px-2 py-0.5 rounded-md border border-blue-100">Campanha: "{{ $campanha->titulo }}"</span>
                        @endif
                        @if(request('titulo'))
                            <span class="bg-slate-100 text-slate-700 text-[9px] font-bold px-2 py-0.5 rounded-md border border-slate-200">Tema: "{{ request('titulo') }}"</span>
                        @endif
                        @if(request('aluno_nome'))
                            <span class="bg-slate-100 text-slate-700 text-[9px] font-bold px-2 py-0.5 rounded-md border border-slate-200">Aluno: "{{ request('aluno_nome') }}"</span>
                        @endif
                        @if(request('status'))
                            <span class="bg-slate-100 text-slate-700 text-[9px] font-bold px-2 py-0.5 rounded-md border border-slate-200">Status: {{ strtoupper(request('status')) }}</span>
                        @endif
                        @if(request('versao_count'))
                            <span class="bg-amber-50 text-amber-700 text-[9px] font-bold px-2 py-0.5 rounded-md border border-amber-100">Versões {{ request('versao_count_operator') == 'maior' ? '>' : '=' }} {{ request('versao_count') }}</span>
                        @endif
                        @if(request('data_inicio'))
                            <span class="bg-purple-50 text-purple-700 text-[9px] font-bold px-2 py-0.5 rounded-md border border-purple-100">De {{ date('d/m/Y', strtotime(request('data_inicio'))) }} até {{ date('d/m/Y', strtotime(request('data_fim'))) }}</span>
                        @endif
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full lg:w-auto">
                {{-- Card Entregas --}}
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-center">
                    <span class="text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Total Exibido</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-black text-slate-900">{{ $anteprojetos->total() }}</span>
                        <span class="text-xs font-bold text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded">Registros</span>
                    </div>
                </div>

                {{-- Card Prazo Dinâmico --}}
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-center">
                    <span class="text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Data Limite</span>
                    <span class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="far fa-calendar-alt text-blue-500"></i>
                        {{ $campanha ? \Carbon\Carbon::parse($campanha->prazo_submissao)->format('d/m/Y') : 'Nenhuma Selecionada' }}
                    </span>
                </div>

                {{-- Status do Prazo --}}
                @if($campanha)
                    @php
                        $diasRestantes = \Carbon\Carbon::now()->diffInDays($campanha->prazo_submissao, false);
                        $isExpired = $diasRestantes < 0;
                    @endphp
                    <div class="{{ $isExpired ? 'bg-red-50 border-red-100' : 'bg-emerald-50 border-emerald-100' }} p-4 rounded-2xl border flex flex-col justify-center col-span-2 md:col-span-1 text-center md:text-left">
                        <span class="text-[10px] uppercase font-black {{ $isExpired ? 'text-red-400' : 'text-emerald-500' }} tracking-widest">Estado da Campanha</span>
                        <span class="text-xs font-black {{ $isExpired ? 'text-red-700' : 'text-emerald-700' }}">
                            {{ $isExpired ? 'SUBMISSÕES ENCERRADAS' : 'RECEBENDO PROJETOS' }}
                        </span>
                    </div>
                @else
                    <div class="bg-slate-50 border-slate-200 p-4 rounded-2xl border flex flex-col justify-center col-span-2 md:col-span-1 text-center md:text-left">
                        <span class="text-[10px] uppercase font-black text-slate-400 tracking-widest">Modo de Exibição</span>
                        <span class="text-xs font-black text-slate-600">FILTRO GLOBAL ATIVO</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- LISTAGEM --}}
    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[11px] font-black uppercase text-slate-400 tracking-widest">Identificação do Aluno / Campanha</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase text-slate-400 tracking-widest">Destaque da Versão</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase text-slate-400 tracking-widest text-center">Progresso</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase text-slate-400 tracking-widest text-right">Ações de Gestão</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($anteprojetos as $item)
                        @php $ultimaVersao = $item->versoes->last(); @endphp
                        <tr class="hover:bg-blue-50/30 transition-all duration-300 group">
                            {{-- Coluna Aluno --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-2xl flex items-center justify-center font-bold text-sm shadow-md transition-transform group-hover:scale-110">
                                            {{ substr($item->estudante->user->name ?? 'AA', 0, 2) }}
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-lg shadow-sm border border-slate-100 flex items-center justify-center">
                                            <i class="fas fa-graduation-cap text-[8px] text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-slate-900 group-hover:text-blue-700 transition-colors">{{ $item->titulo }}</p>
                                        <div class="flex flex-col gap-0.5 mt-0.5">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-semibold text-slate-500">{{ $item->estudante->user->name ?? 'Estudante não identificado' }}</span>
                                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item->estudante->curso->nome ?? 'Sem Curso' }}</span>
                                            </div>
                                            <span class="text-[10px] font-medium text-blue-500">[Campanha: {{ $item->campanha->titulo ?? 'Geral' }}]</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Coluna Versão --}}
                            <td class="px-6 py-6">
                                <div class="inline-flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 group-hover:bg-white transition-colors">
                                    <div class="text-center pr-3 border-r border-slate-200">
                                        <span class="block text-[10px] font-black text-slate-400 uppercase leading-none">Versão</span>
                                        <span class="text-lg font-black text-blue-600">{{ $item->versoes->count() }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        @if($ultimaVersao)
                                            <span class="text-xs font-bold text-slate-700">{{ $ultimaVersao->created_at->format('d/m/Y') }}</span>
                                            <span class="text-[10px] font-medium text-slate-400 italic">Enviado às {{ $ultimaVersao->created_at->format('H:i') }}</span>
                                        @else
                                            <span class="text-xs font-bold text-slate-400 italic">Sem histórico</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Coluna Status --}}
                            <td class="px-6 py-6">
                                <div class="flex flex-col items-center gap-1">
                                    @php
                                        $statusConfig = [
                                            'submetido' => ['color' => 'blue', 'icon' => 'fa-paper-plane'],
                                            'em_analise' => ['color' => 'indigo', 'icon' => 'fa-spinner text-spin'],
                                            'aprovado'  => ['color' => 'emerald', 'icon' => 'fa-check-double'],
                                            'rejeitado' => ['color' => 'red', 'icon' => 'fa-times'],
                                            'melhorias' => ['color' => 'amber', 'icon' => 'fa-pen-fancy'],
                                        ][$item->status] ?? ['color' => 'slate', 'icon' => 'fa-info-circle'];
                                        $c = $statusConfig['color'];
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-{{ $c }}-50 border-{{ $c }}-100 text-{{ $c }}-600 flex items-center gap-2">
                                        <i class="fas {{ $statusConfig['icon'] }} text-[9px]"></i>
                                        {{ str_replace('_', ' ', $item->status) }}
                                    </span>
                                </div>
                            </td>

                            {{-- Coluna Ações --}}
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    @if($ultimaVersao && $ultimaVersao->arquivo)
                                        <a href="{{ asset('storage/'.$ultimaVersao->arquivo) }}" target="_blank" 
                                           class="group/btn relative w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white rounded-xl transition-all duration-300" 
                                           title="Visualizar PDF">
                                            <i class="fas fa-file-pdf text-sm"></i>
                                            <span class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover/btn:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">Ver Documento</span>
                                        </a>
                                    @endif

                                    <a href="{{ route('anteprojetosAdmin.historico', $item->id) }}" 
                                       class="w-10 h-10 flex items-center justify-center bg-slate-900 text-white hover:bg-blue-600 rounded-xl transition-all duration-300 shadow-lg shadow-slate-200" 
                                       title="Ver todo o histórico">
                                        <i class="fas fa-layer-group text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center">
                                <div class="relative inline-block mb-4">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-200">
                                        <i class="fas fa-inbox text-4xl"></i>
                                    </div>
                                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full shadow-sm flex items-center justify-center border border-slate-100">
                                        <i class="fas fa-search text-xs text-slate-300"></i>
                                    </div>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">Nenhuma submissão correspondente</h3>
                                <p class="text-slate-400 text-sm max-w-xs mx-auto">Abra os filtros avançados e selecione critérios de pesquisa para refinar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        @if($anteprojetos->hasPages())
        <div class="px-6 py-5 bg-slate-50/50 border-t border-slate-100">
            {{ $anteprojetos->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL INTERATIVO DE FILTROS AVANÇADOS --}}
    <div x-show="openFilterModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-cloak x-transition>
        
        <div class="bg-white w-full max-w-xl rounded-[2rem] shadow-2xl overflow-hidden" @click.away="openFilterModal = false">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center">
                <div>
                    <h3 class="font-bold uppercase tracking-widest text-xs">Filtros Gerais Avançados</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Selecione uma campanha ou filtre por dados biográficos/quantitativos</p>
                </div>
                <button @click="openFilterModal = false" class="text-white/50 hover:text-white transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('anteprojetosAdmin.index') }}" class="p-8 grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                {{-- [NOVO] Filtro por Campanha Ativa --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Campanha Científica de Origem</label>
                    <select name="campanha_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Todas as Campanhas do Sistema</option>
                        @foreach($campanhasDisponiveis as $camp)
                            <option value="{{ $camp->id }}" {{ request('campanha_id') == $camp->id ? 'selected' : '' }}>
                                {{ $camp->titulo }} ({{ $camp->curso->nome ?? 'Sem Curso' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por Título do Tema --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Título / Palavra-Chave do Tema</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                        <input type="text" name="titulo" value="{{ request('titulo') }}" placeholder="Ex: Machine Learning"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                </div>

                {{-- Filtro por Nome do Estudante --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Nome do Estudante</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                        <input type="text" name="aluno_nome" value="{{ request('aluno_nome') }}" placeholder="Ex: Mário"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                </div>

                {{-- Filtro por Status --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Status Avaliativo</label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Todos os Estados</option>
                        <option value="submetido" {{ request('status') == 'submetido' ? 'selected' : '' }}>Submetido</option>
                        <option value="em_analise" {{ request('status') == 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                        <option value="aprovado" {{ request('status') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                        <option value="melhorias" {{ request('status') == 'melhorias' ? 'selected' : '' }}>Necessita de Melhorias</option>
                        <option value="rejeitado" {{ request('status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                    </select>
                </div>

                {{-- Filtro Versões --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Operador de Versões</label>
                    <select name="versao_count_operator" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="igual" {{ request('versao_count_operator') == 'igual' ? 'selected' : '' }}>Exatamente Igual a</option>
                        <option value="maior" {{ request('versao_count_operator') == 'maior' ? 'selected' : '' }}>Maior que (>)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Qtd. Versões</label>
                    <input type="number" name="versao_count" value="{{ request('versao_count') }}" placeholder="Ex: 1" min="1"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                {{-- Filtro Datas --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Data Inicial de Envio</label>
                    <input type="date" name="data_inicio" value="{{ request('data_inicio') }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Data Final de Envio</label>
                    <input type="date" name="data_fim" value="{{ request('data_fim') }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div class="sm:col-span-2 flex gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openFilterModal = false" class="flex-1 px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs uppercase transition hover:bg-slate-200">
                        Fechar
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold text-xs uppercase shadow-lg shadow-blue-100 transition hover:bg-blue-700">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection