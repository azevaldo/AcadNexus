@extends('app.private.dashboard.app.templete')

@section('content')
<main class="flex-1 overflow-y-auto bg-slate-50 p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER --}}
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight">
                Gestão de Departamento: <span class="text-indigo-600">{{ $coordenador->departamento->nome }}</span>
            </h1>
            <p class="text-slate-500 font-medium italic">Relatório Consolidado de Atividades Acadêmicas</p>
        </div>

        {{-- CARDS DE MÉTRICAS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                <p class="text-[10px] font-black uppercase text-slate-400">Total Estudantes</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $stats['total_estudantes'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                <p class="text-[10px] font-black uppercase text-slate-400">Campanhas Criadas</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $stats['total_campanhas'] }}</h3>
            </div>
            <div class="bg-amber-50 p-6 rounded-3xl shadow-sm border border-amber-100">
                <p class="text-[10px] font-black uppercase text-amber-600">Alunos sem Orientador</p>
                <h3 class="text-2xl font-black text-amber-800">{{ $stats['estudantes_sem_tutor'] }}</h3>
            </div>
            <div class="bg-indigo-600 p-6 rounded-3xl shadow-xl">
                <p class="text-[10px] font-black uppercase text-indigo-200">Total Anteprojetos</p>
                <h3 class="text-2xl font-black text-white">{{ $stats['anteprojetos'] }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- TABELA DE CAMPANHAS --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-black text-xs uppercase text-slate-700">Campanhas por Curso</h3>
                    <a href="#" class="text-[10px] font-bold text-indigo-600 hover:underline">Ver Todas</a>
                </div>
                <div class="p-4 space-y-4">
                    @foreach($campanhasAtivas as $campanha)
                    <div class="p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black px-2 py-1 bg-indigo-100 text-indigo-700 rounded-md uppercase">
                                {{ $campanha->curso->nome }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 italic">Prazo: {{ \Carbon\Carbon::parse($campanha->prazo_submissao)->format('d/m/Y') }}</span>
                        </div>
                        <h4 class="text-sm font-black text-slate-800">{{ $campanha->titulo }}</h4>
                        <div class="flex gap-4 mt-3">
                            <div class="text-[10px] font-bold text-slate-500">
                                <i class="fas fa-file-alt mr-1"></i> {{ $campanha->anteprojetos_count }} Propostas
                            </div>
                            <div class="text-[10px] font-bold text-slate-500">
                                <i class="fas fa-user-graduate mr-1"></i> {{ $campanha->estudantes_count }} Inscritos
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- CARGA DOCENTE --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                    <h3 class="font-black text-xs uppercase text-slate-700">Carga de Orientação do Departamento</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase border-b border-slate-50">
                                <th class="px-6 py-4">Professor</th>
                                <th class="px-6 py-4">Orientandos</th>
                                <th class="px-6 py-4 text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($professores as $prof)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-slate-800">{{ $prof->user->name }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-indigo-600">{{ $prof->total_orientandos }}</span>
                                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden w-20">
                                            <div class="bg-indigo-500 h-full" style="width: {{ ($prof->total_orientandos / $prof->max_orientacoes) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($prof->total_orientandos >= $prof->max_orientacoes)
                                        <span class="px-2 py-1 bg-red-100 text-red-600 text-[9px] font-black rounded-full uppercase">Carga Máxima</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-600 text-[9px] font-black rounded-full uppercase">Disponível</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection