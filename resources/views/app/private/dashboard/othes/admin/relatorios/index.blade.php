@extends('app.private.dashboard.app.templete')

@section('content')
<main class="flex-1 overflow-y-auto bg-slate-50 p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER --}}
        <div class="mb-10">
            <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight">Relatório <span class="text-indigo-600">Institucional</span></h1>
            <p class="text-slate-500 font-medium">Visão geral de todos os departamentos e processos acadêmicos.</p>
        </div>

        {{-- CARDS GLOBAIS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Total de Estudantes</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-slate-800">{{ $stats['total_estudantes'] }}</h3>
                    <span class="text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg text-xs font-bold">{{ $stats['taxa_orientacao'] }}% com tutor</span>
                </div>
            </div>

            <div class="bg-indigo-600 p-6 rounded-3xl shadow-lg shadow-indigo-100 text-white">
                <p class="text-[10px] font-black text-indigo-200 uppercase mb-2">Anteprojetos Totais</p>
                <h3 class="text-3xl font-black">{{ $stats['total_anteprojetos'] }}</h3>
                <p class="text-indigo-200 text-[10px] mt-2 italic text-right">Submetidos no sistema</p>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Docentes Ativos</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $stats['total_professores'] }}</h3>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Usuários Totais</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $stats['total_usuarios'] }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- RANKING DE ORIENTADORES --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50">
                        <h4 class="text-xs font-black uppercase text-slate-800 italic">Top 10 Orientadores</h4>
                    </div>
                    <div class="p-4 space-y-4">
                        @foreach($top_orientadores as $prof)
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-2xl transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black">
                                    {{ substr($prof->user->name, 0, 2) }}
                                </div>
                                <span class="text-xs font-bold text-slate-700">{{ $prof->user->name }}</span>
                            </div>
                            <span class="bg-indigo-600 text-white text-[10px] px-2 py-1 rounded-full font-black">
                                {{ $prof->total }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- DEPARTAMENTOS E STATUS --}}
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 bg-slate-900 text-white flex justify-between items-center">
                        <h3 class="text-xs font-black uppercase tracking-widest">Densidade por Departamento</h3>
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="p-4">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-400 uppercase">
                                    <th class="px-4 py-3">Departamento</th>
                                    <th class="px-4 py-3 text-center">Estudantes</th>
                                    <th class="px-4 py-3">Status Visual</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($departamentos_stats as $dep)
                                <tr>
                                    <td class="px-4 py-4 text-sm font-bold text-slate-700">{{ $dep->nome }}</td>
                                    <td class="px-4 py-4 text-sm text-center font-black text-indigo-600">{{ $dep->estudantes_count }}</td>
                                    <td class="px-4 py-4">
                                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                            <div class="bg-indigo-500 h-full" style="width: {{ ($dep->estudantes_count / max($stats['total_estudantes'], 1)) * 100 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ÚLTIMOS ANTEPROJETOS --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-50">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-800">Fluxo Recente de Anteprojetos</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                        @foreach($ultimos_anteprojetos as $projeto)
                        <div class="p-4 border border-slate-100 rounded-2xl flex flex-col justify-between">
                            <div>
                                <p class="text-[10px] font-black text-indigo-600 uppercase">{{ $projeto->campanha->nome }}</p>
                                <h5 class="text-sm font-bold text-slate-800 mt-1">{{ $projeto->titulo }}</h5>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-3 italic">Por: {{ $projeto->estudante->user->name }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection