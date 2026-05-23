@extends('app.private.dashboard.app.templete')

@section('content')
<main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-indigo-50/40 p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 text-xs font-black uppercase tracking-widest mb-4">
                    <i class="fas fa-chart-pie mr-2"></i> Central de Consultas & Relatórios
                </div>
                <h1 class="text-4xl font-black text-gray-900 italic">Meu Resumo <span class="text-indigo-600">Acadêmico</span></h1>
                <p class="text-gray-500 text-sm mt-2">Visão consolidada de anteprojetos, orientações e progresso.</p>
            </div>
            
            <div class="flex gap-3">
                <button onclick="window.print()" class="bg-white border border-gray-200 text-gray-700 px-6 py-3 rounded-2xl font-black text-xs uppercase hover:bg-gray-50 transition shadow-sm">
                    <i class="fas fa-print mr-2"></i> Imprimir Tudo
                </button>
            </div>
        </div>

        {{-- GRID DE STATS (KPIs) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            {{-- Card 1 --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-xl border border-gray-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Anteprojetos Submetidos</p>
                    <h3 class="text-3xl font-black text-gray-800">{{ $stats['anteprojetos_contagem'] }}</h3>
                </div>
                <i class="fas fa-file-alt absolute -right-4 -bottom-4 text-6xl text-gray-50 group-hover:text-indigo-50 transition-colors"></i>
            </div>

            {{-- Card 2 --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-xl border border-gray-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Temas Sugeridos (IA)</p>
                    <h3 class="text-3xl font-black text-indigo-600">{{ $stats['temas_ia'] }}</h3>
                </div>
                <i class="fas fa-robot absolute -right-4 -bottom-4 text-6xl text-gray-50 group-hover:text-indigo-50 transition-colors"></i>
            </div>

            {{-- Card 3 --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-xl border border-gray-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Temas Favoritados</p>
                    <h3 class="text-3xl font-black text-orange-500">{{ $stats['favoritos_ia'] }}</h3>
                </div>
                <i class="fas fa-star absolute -right-4 -bottom-4 text-6xl text-gray-50 group-hover:text-indigo-50 transition-colors"></i>
            </div>

            {{-- Card 4 --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-xl border border-gray-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase text-gray-400 mb-1">Campanhas Ativas</p>
                    <h3 class="text-3xl font-black text-green-600">{{ $stats['campanhas_ativas'] }}</h3>
                </div>
                <i class="fas fa-bullhorn absolute -right-4 -bottom-4 text-6xl text-gray-50 group-hover:text-indigo-50 transition-colors"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- COLUNA ESQUERDA: PERFIL E CURSO --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-indigo-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-300 mb-6">Ficha de Identificação</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-indigo-200">Curso</p>
                                <p class="text-lg font-bold">{{ $estudante->curso->nome ?? 'Não definido' }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-indigo-200">Matrícula</p>
                                    <p class="font-bold">{{ $estudante->numero_matricula }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-200">Ano/Frequência</p>
                                    <p class="font-bold">{{ $estudante->ano_frequencia }}º Ano</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-indigo-200">Regime</p>
                                <p class="font-bold text-capitalize">{{ $estudante->regime }}</p>
                            </div>
                        </div>
                    </div>
                    <i class="fas fa-graduation-cap absolute -bottom-10 -right-10 text-[180px] text-white/5 rotate-12"></i>
                </div>

                {{-- ORIENTADOR --}}
                <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-gray-100">
                    <h4 class="text-xs font-black uppercase text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-user-tie mr-2 text-indigo-600"></i> Meu Orientador
                    </h4>
                    
                    @if($estudante->tutorRelacao)
                        <div class="flex items-center p-4 bg-gray-50 rounded-2xl">
                            <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black">
                                {{ substr($estudante->tutorRelacao->tutor->user->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-black text-gray-800">{{ $estudante->tutorRelacao->tutor->user->name }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-tighter italic">Tutor Alocado</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6 border-2 border-dashed border-gray-100 rounded-2xl">
                            <p class="text-xs text-gray-400">Nenhum orientador alocado até o momento.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- COLUNA DIREITA: CONSULTAS DETALHADAS --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- LISTA DE ANTEPROJETOS --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-black uppercase tracking-widest text-xs text-gray-800">Meus Anteprojetos</h3>
                        <span class="bg-white px-3 py-1 rounded-full text-[10px] font-black text-gray-400 border border-gray-200">Total: {{ $estudante->anteprojetos->count() }}</span>
                    </div>
                    
                    <div class="divide-y divide-gray-50">
                        @forelse($estudante->anteprojetos as $proj)
                            <div class="p-8 hover:bg-gray-50 transition-all group">
                                <div class="flex items-start justify-between">
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-3">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                            <h4 class="font-black text-gray-800 text-sm group-hover:text-indigo-600 transition">{{ $proj->titulo }}</h4>
                                        </div>
                                        <p class="text-xs text-gray-500 leading-relaxed max-w-xl">{{ Str::limit($proj->resumo, 150) }}</p>
                                        <div class="flex gap-4 mt-4">
                                            <span class="text-[10px] font-black text-gray-400 uppercase"><i class="far fa-calendar-alt mr-1"></i> {{ $proj->created_at->format('d/m/Y') }}</span>
                                            <span class="text-[10px] font-black text-gray-400 uppercase"><i class="far fa-file-pdf mr-1"></i> Anteprojeto_v1.pdf</span>
                                        </div>
                                    </div>
                                    <button class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-gray-400 text-sm italic">
                                Nenhum anteprojeto submetido.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- CAMPANHAS RELACIONADAS --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 flex items-center bg-gray-50/50">
                        <h3 class="font-black uppercase tracking-widest text-xs text-gray-800">Participação em Campanhas</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                        @foreach($estudante->campanhas as $camp)
                            <div class="border border-gray-100 rounded-2xl p-5 hover:border-indigo-200 hover:bg-indigo-50/30 transition shadow-sm">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">
                                        <i class="fas fa-flag"></i>
                                    </div>
                                    <h5 class="ml-3 font-black text-gray-800 text-xs uppercase">{{ $camp->nome }}</h5>
                                </div>
                                <p class="text-[11px] text-gray-500 line-clamp-2">Campanha de submissão de temas para o ano lectivo {{ date('Y') }}.</p>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection