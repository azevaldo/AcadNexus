@extends('app.private.dashboard.app.templete')

@section('title', 'Lista de Utilizadores')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 md:py-8" x-data="{ openFilterModal: false }">

    {{-- CABEÇALHO DA TELA --}}
    <div class="mb-6 md:mb-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 md:gap-4 mb-2">
                    {{-- Ícone de Consulta/Listagem Adaptado --}}
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200 shrink-0">
                        <i class="fas fa-search-plus text-lg md:text-xl"></i>
                    </div>
                    
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Lista Geral de Utilizadores
                    </h1>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 pl-0 sm:pl-16 mt-2">
                    <p class="text-slate-500 text-xs md:text-sm font-medium flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        Exibindo: <span class="text-slate-800 font-bold">Últimos registros cadastrados primeiro</span>
                    </p>
                    
                    {{-- Opções de Filtro Coadjuvantes --}}
                    <div class="flex flex-wrap items-center gap-2 mt-1 sm:mt-0 border-t sm:border-t-0 sm:border-l pt-2 sm:pt-0 sm:pl-3 border-slate-200">
                        <button @click="openFilterModal = true" 
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white text-[10px] font-bold uppercase rounded-lg transition shadow-sm">
                            <i class="fas fa-filter text-[9px]"></i> Pesquisar na Lista
                        </button>

                        @if(request()->anyFilled(['name', 'email', 'role']))
                            <a href="{{ route('admin.usuarios.recentes') }}" 
                               class="text-[10px] text-red-500 hover:text-red-700 font-bold uppercase underline decoration-2 underline-offset-4 transition pl-1">
                                Limpar Pesquisa
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Contador Totalizador --}}
            <div class="bg-white p-3 md:p-4 rounded-xl md:rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-center sm:w-44 shrink-0">
                <span class="text-[9px] md:text-[10px] uppercase font-black text-slate-400 tracking-widest mb-0.5">Total Encontrado</span>
                <div class="flex items-baseline gap-1.5">
                    {{-- O método ->total() agora funcionará perfeitamente por conta do ->paginate() no controller --}}
                    <span class="text-xl md:text-2xl font-black text-slate-900">{{ $recentUsers->total() }}</span>
                    <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded">Utilizadores</span>
                </div>
            </div>
        </div>
    </div>

    {{-- TABELA DE LISTAGEM DIRETA (MOBILE-READY) --}}
    <div class="bg-white rounded-xl md:rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto scrolling-touch">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                        <th class="px-6 md:px-8 py-4.5">Nome</th>
                        <th class="px-6 md:px-8 py-4.5">E-mail</th>
                        <th class="px-6 md:px-8 py-4.5">Nível</th>
                        <th class="px-6 md:px-8 py-4.5">Data de Registo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentUsers as $u)
                        <tr class="hover:bg-blue-50/20 transition-colors group">
                            {{-- Nome --}}
                            <td class="px-6 md:px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-slate-100 text-slate-700 rounded-lg flex items-center justify-center font-bold text-xs group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                        {{ substr($u->name, 0, 2) }}
                                    </div>
                                    <p class="text-sm font-bold text-slate-800 group-hover:text-blue-700 transition-colors">{{ $u->name }}</p>
                                </div>
                            </td>
                            
                            {{-- E-mail --}}
                            <td class="px-6 md:px-8 py-4">
                                <span class="text-xs text-slate-500 font-medium">{{ $u->email }}</span>
                            </td>
                            
                            {{-- Nível --}}
                            <td class="px-6 md:px-8 py-4">
                                @php
                                    $roleName = $u->roles->first()->name ?? 'Utilizador';
                                    $colorMap = [
                                        'admin' => 'bg-red-50 border-red-100 text-red-600',
                                        'coordenador' => 'bg-amber-50 border-amber-100 text-amber-600',
                                        'estudante' => 'bg-blue-50 border-blue-100 text-blue-600',
                                    ];
                                    $style = $colorMap[strtolower($roleName)] ?? 'bg-slate-50 border-slate-100 text-slate-600';
                                @endphp
                                <span class="px-2.5 py-1 rounded-md border text-[9px] font-black uppercase {{ $style }}">
                                    {{ $roleName }}
                                </span>
                            </td>
                            
                            {{-- Data --}}
                            <td class="px-6 md:px-8 py-4 text-xs text-slate-400 font-medium">
                                {{ $u->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-300 mb-3">
                                    <i class="fas fa-users-slash text-xl"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold text-base">Nenhum utilizador registado</h3>
                                <p class="text-slate-400 text-xs max-w-xs mx-auto mt-0.5">O sistema não possui contas criadas ou os filtros aplicados não retornaram dados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINAÇÃO DO LARAVEL --}}
        @if($recentUsers->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $recentUsers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL ADAPTÁVEL DE PESQUISA AUXILIAR --}}
    <div x-show="openFilterModal" 
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/60 backdrop-blur-sm p-0 sm:p-4"
         x-cloak x-transition>
        
        <div class="bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl shadow-2xl overflow-hidden" @click.away="openFilterModal = false">
            <div class="p-5 bg-slate-900 text-white flex justify-between items-center">
                <div>
                    <h3 class="font-bold uppercase tracking-widest text-xs">Pesquisar na Lista</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Refine a exibição dos dados da tabela</p>
                </div>
                <button @click="openFilterModal = false" class="text-white/50 hover:text-white p-1 transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('admin.usuarios.recentes') }}" class="p-5 space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Filtrar por Nome</label>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Nome do utilizador"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-xs md:text-sm outline-none transition focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Filtrar por E-mail</label>
                    <input type="text" name="email" value="{{ request('email') }}" placeholder="Endereço de e-mail"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2 px-3 text-xs md:text-sm outline-none transition focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Filtrar por Nível</label>
                    <select name="role" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs md:text-sm outline-none transition focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos os Níveis</option>
                        @foreach($rolesDisponiveis as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openFilterModal = false" class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs uppercase transition hover:bg-slate-200">
                        Fechar
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-xs uppercase shadow-md transition hover:bg-blue-700">
                        Pesquisar
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