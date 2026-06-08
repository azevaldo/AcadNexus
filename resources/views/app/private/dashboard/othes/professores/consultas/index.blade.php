@extends('app.private.dashboard.app.templete')

@section('title', 'Filtrar Professores')

@section('content')
<div class="p-8 max-w-7xl mx-auto" x-data="{ openFilterModal: false }">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Busca Avançada de Professores
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                Utilize os filtros abaixo para realizar pesquisas cruzadas na base de dados de docentes.
            </p>
            
            <div class="flex flex-wrap items-center gap-2 mt-4">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtros Ativos:</span>
                
                @if(request('nome'))
                    <span class="bg-slate-100 text-slate-700 text-[10px] px-2.5 py-1 rounded-lg font-bold border">
                        Nome: "{{ request('nome') }}"
                    </span>
                @endif
                @if(request('email'))
                    <span class="bg-slate-100 text-slate-700 text-[10px] px-2.5 py-1 rounded-lg font-bold border">
                        Email: "{{ request('email') }}"
                    </span>
                @endif
                @if(request('departamento_id'))
                    <span class="bg-blue-50 text-blue-700 text-[10px] px-2.5 py-1 rounded-lg font-bold border border-blue-200">
                        Departamento ID: {{ request('departamento_id') }}
                    </span>
                @endif
                @if(request('area_cientifica_id'))
                    <span class="bg-purple-50 text-purple-700 text-[10px] px-2.5 py-1 rounded-lg font-bold border border-purple-200">
                        Área Científica ID: {{ request('area_cientifica_id') }}
                    </span>
                @endif
                @if(request('status'))
                    <span class="bg-amber-50 text-amber-700 text-[10px] px-2.5 py-1 rounded-lg font-bold border border-amber-200">
                        Estatuto: {{ request('status') == 'ativo' ? 'Ativo' : 'Suspenso' }}
                    </span>
                @endif

                <button @click="openFilterModal = true" 
                        class="ml-2 flex items-center gap-2 px-3 py-1 bg-slate-800 hover:bg-slate-700 text-white text-[10px] font-bold uppercase rounded-lg transition shadow-sm">
                    <i class="fas fa-filter text-[9px]"></i> Configurar Filtros
                </button>

                @if(request()->hasAny(['nome', 'email', 'departamento_id', 'area_cientifica_id', 'status']))
                    <a href="{{ route('professores.index') }}" 
                       class="text-[10px] text-red-500 hover:text-red-700 font-bold uppercase underline decoration-2 transition ml-2">
                        Limpar Filtros
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white shadow-sm border border-slate-100 rounded-[2rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-black uppercase text-slate-400 tracking-widest">
                        <th class="px-6 py-5">Nome / Email</th>
                        <th class="px-6 py-5">Departamentos e Áreas de Atuação</th>
                        <th class="px-6 py-5 text-center">Estatuto</th>
                        <th class="px-6 py-5 text-right">Ações de Consulta</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($professores as $prof)
                    <tr class="transition-colors hover:bg-slate-50/30 {{ $prof->user->acesso ? '' : 'bg-gray-100/70 opacity-65' }}">
                        
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center text-slate-600 font-bold shadow-sm">
                                    {{ substr($prof->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold {{ $prof->user->acesso ? 'text-slate-800' : 'text-slate-400 line-through' }}">
                                        {{ $prof->user->name }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 font-medium">{{ $prof->user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1 mb-1">
                                @foreach($prof->departamentos as $d)
                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-[10px] font-bold border border-slate-200">
                                        {{ $d->nome }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="flex flex-wrap gap-1">
                                @foreach($prof->areas as $a)
                                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase">
                                        {{ $a->nome }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center">
                            @if($prof->user->acesso)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                    <span class="w-1 h-1 rounded-full bg-green-500"></span> Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">
                                    <span class="w-1 h-1 rounded-full bg-red-400"></span> Suspenso
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                
                                <a href="{{ route('professores.show', $prof->id) }}"
                                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-50 hover:text-blue-600 border border-slate-200 transition"
                                   title="Ver Perfil do Professor">
                                    <i class="fas fa-info-circle text-xs"></i>
                                </a>
                                @hasanyrole('admin|professor|coordenador')
                                <a href="{{ route('professores.estudantesAcompanhados', $prof->id) }}"
                                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-50 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 border border-slate-200 transition relative"
                                   title="Ver Alunos em Acompanhamento">
                                    <i class="fas fa-user-graduate text-xs"></i>
                                    @if($prof->acompanhamentos->count() > 0)
                                        <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-bold">
                                            {{ $prof->acompanhamentos->count() }}
                                        </span>
                                    @endif
                                </a>
                                @endhasanyrole

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-search text-4xl text-slate-200 mb-4"></i>
                                <p class="text-slate-400 font-medium">Nenhum professor corresponde aos critérios de pesquisa.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($professores->hasPages())
            <div class="px-6 py-5 bg-slate-50/50 border-t border-slate-100">
                {{ $professores->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <div x-show="openFilterModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-cloak x-transition>
        
        <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden" @click.away="openFilterModal = false">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center">
                <div>
                    <h3 class="font-bold uppercase tracking-widest text-xs">Todos os Filtros Disponíveis</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Combine parâmetros biográficos, departamentais ou estatutos</p>
                </div>
                <button @click="openFilterModal = false" class="text-white/50 hover:text-white transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('professores.consultas.index') }}" class="p-8 grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Nome ou Apelido</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                        <input type="text" name="nome" value="{{ request('nome') }}" placeholder="Buscar por nome..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Email Institucional</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                        <input type="text" name="email" value="{{ request('email') }}" placeholder="Ex: docente@universidade.com"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Departamento</label>
                    <input type="number" name="departamento_id" value="{{ request('departamento_id') }}" placeholder="ID do Departamento"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Área Científica</label>
                    <input type="number" name="area_cientifica_id" value="{{ request('area_cientifica_id') }}" placeholder="ID da Área"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Estatuto no Sistema</label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Todos os Estatutos</option>
                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Apenas Ativos</option>
                        <option value="suspenso" {{ request('status') == 'suspenso' ? 'selected' : '' }}>Apenas Suspensos</option>
                    </select>
                </div>

                <div class="sm:col-span-2 flex justify-end gap-3 mt-4 border-t pt-5">
                    <button type="button" @click="openFilterModal = false" class="px-5 py-2.5 text-sm bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">
                        Fechar
                    </button>
                    <button type="submit" class="px-6 py-2.5 text-sm bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection