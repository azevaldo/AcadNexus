 @extends('app.private.dashboard.app.templete')

@section('content')
<main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-indigo-50/40 p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER PROFESSOR --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest mb-4 shadow-lg shadow-indigo-200">
                    <i class="fas fa-chalkboard-teacher mr-2"></i> Painel do Orientador
                </div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">Portal de <span class="text-indigo-600">Gestão Acadêmica</span></h1>
                <p class="text-gray-500 text-sm mt-2 font-medium">
                    Docente: **{{ $professor->user->name }}** @if(Auth::user()->roles->isNotEmpty())
                        <span class="ml-2 px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-[10px] uppercase font-black">
                            {{ Auth::user()->roles->first()->name }}
                        </span>
                    @endif
                </p>
            </div>
            
            <div class="flex gap-3">
                <button class="bg-white border-2 border-indigo-100 text-indigo-600 px-6 py-3 rounded-2xl font-black text-xs uppercase hover:bg-indigo-50 transition shadow-sm">
                    <i class="fas fa-file-export mr-2"></i> Exportar Pauta
                </button>
            </div>
        </div>

        {{-- CARDS DE PERFORMANCE DOCENTE --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-indigo-50 relative group overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Orientandos Ativos</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $stats['total_orientandos'] ?? 0 }}</h3>
                </div>
                <i class="fas fa-users absolute -right-4 -bottom-4 text-6xl text-gray-50 group-hover:text-indigo-50 transition-all"></i>
            </div>

            <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-indigo-50 relative group overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Co-orientações</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $stats['total_coorientandos'] ?? 0 }}</h3>
                </div>
                <i class="fas fa-hands-helping absolute -right-4 -bottom-4 text-6xl text-gray-50 group-hover:text-indigo-50 transition-all"></i>
            </div>

            <div class="bg-indigo-600 p-6 rounded-[2.5rem] shadow-xl relative group overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase text-indigo-200 mb-1">Registros de TCC</p>
                    <h3 class="text-3xl font-black text-white">{{ $stats['total_acompanhamentos'] ?? 0 }}</h3>
                </div>
                <i class="fas fa-history absolute -right-4 -bottom-4 text-6xl text-white/10 group-hover:scale-110 transition-all"></i>
            </div>

            <div class="bg-white p-6 rounded-[2.5rem] shadow-xl border border-indigo-50 relative group overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Áreas Científicas</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $stats['total_areas'] ?? 0 }}</h3>
                </div>
                <i class="fas fa-microscope absolute -right-4 -bottom-4 text-6xl text-gray-50 group-hover:text-indigo-50 transition-all"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- PERFIL E DEPARTAMENTOS --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-gray-100">
                    <h4 class="text-xs font-black uppercase text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-building mr-2 text-indigo-600"></i> Lotação Acadêmica
                    </h4>
                    <div class="space-y-4">
                        @foreach($professor->departamentos as $dep)
                            <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 group hover:bg-indigo-600 transition-all">
                                <p class="text-xs font-black text-indigo-600 uppercase group-hover:text-white transition">Departamento</p>
                                <p class="text-sm font-bold text-gray-700 group-hover:text-indigo-100 transition">{{ $dep->nome }}</p>
                            </div>
                        @endforeach
                    </div>

                    <hr class="my-6 border-gray-100">

                    <h4 class="text-xs font-black uppercase text-gray-800 mb-4">Especialidades</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($professor->areas as $area)
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] font-black uppercase">{{ $area->nome }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- LISTAGEM DE ESTUDANTES --}}
            <div class="lg:col-span-8 space-y-8">
                
                <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-black uppercase tracking-widest text-xs text-gray-800">Lista de Orientandos (Tutor Principal)</h3>
                        <span class="text-[10px] font-black text-indigo-600">{{ $professor->estudantes->count() }} Alunos</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-8 py-4 text-[10px] font-black uppercase text-gray-400">Estudante</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400">Curso</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase text-gray-400 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($professor->estudantes as $orientado)
                                    <tr class="hover:bg-indigo-50/30 transition-all">
                                        <td class="px-8 py-5">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs">
                                                    {{ substr($orientado->estudante->user->name ?? 'E', 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-sm font-black text-gray-800">{{ $orientado->estudante->user->name }}</p>
                                                    <p class="text-[10px] text-gray-400 font-medium">Mat: {{ $orientado->estudante->numero_matricula }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span class="text-xs font-bold text-gray-600">{{ $orientado->estudante->curso->nome ?? 'N/A' }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <button class="text-indigo-600 hover:text-indigo-900 transition font-black text-[10px] uppercase">
                                                <i class="fas fa-search-plus mr-1"></i> Detalhes
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-10 text-center text-gray-400 italic text-sm">Sem orientandos alocados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- HISTÓRICO DE ACOMPANHAMENTOS --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 bg-gray-900 text-white">
                        <h3 class="font-black uppercase tracking-widest text-xs">Logs de Acompanhamento TCC</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @forelse($professor->acompanhamentos as $acomp)
                            <div class="flex items-center justify-between p-4 border border-gray-50 rounded-2xl hover:border-indigo-100 transition shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-indigo-500 mr-4"></div>
                                    <div>
                                        <p class="text-xs font-black text-gray-800">Interação Registrada</p>
                                        <p class="text-[10px] text-gray-400 italic">{{ $acomp->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] font-black bg-gray-100 px-3 py-1 rounded-full uppercase text-gray-500">Log #{{ $acomp->id }}</span>
                            </div>
                        @empty
                            <p class="p-6 text-center text-gray-400 text-xs italic">Nenhum registro de acompanhamento encontrado.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection