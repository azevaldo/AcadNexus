@extends('app.private.dashboard.app.templete')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden bg-gray-50">
    {{-- Header com Dados Acadêmicos --}}
    <header class="bg-white border-b border-gray-200 p-8 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Olá, {{ Auth::user()->name }} 👋</h2>
                <div class="flex flex-wrap gap-3 mt-2">
                    <span class="bg-indigo-50 text-indigo-700 text-[10px] font-black px-2 py-1 rounded uppercase border border-indigo-100">
                        Matrícula: {{ $estudante->numero_matricula ?? 'N/D' }}
                    </span>
                    <span class="bg-slate-100 text-slate-600 text-[10px] font-black px-2 py-1 rounded uppercase">
                        {{ $estudante->curso->nome ?? 'Curso não vinculado' }}
                    </span>
                    <span class="bg-slate-100 text-slate-600 text-[10px] font-black px-2 py-1 rounded uppercase">
                        {{ $estudante->regime ?? 'Regime N/D' }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::now()->translatedFormat('d \d\e F, Y') }}</p>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 space-y-8">
        
        {{-- SEÇÃO 1: RESUMO DE ORIENTAÇÃO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-3">Orientador Principal</p>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-xs">
                        {{ substr($estudante->tutorRelacao->tutor->user->name ?? '?', 0, 1) }}
                    </div>
                    <span class="text-xs font-black text-gray-700 truncate">
                        {{ $estudante->tutorRelacao->tutor->user->name ?? 'Não atribuído' }}
                    </span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-3">Co-orientador</p>
                <span class="text-xs font-black text-gray-700">
                    {{ $estudante->tutorRelacao->coTutor->user->name ?? 'Nenhum' }}
                </span>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-3">Campanhas Inscritas</p>
                <span class="text-2xl font-black text-gray-800">{{ $estudante->campanhas->count() }}</span>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase mb-3">Anteprojetos</p>
                <span class="text-2xl font-black text-gray-800">{{ $estudante->anteprojetos->count() }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- SEÇÃO 2: ACOMPANHAMENTO DE TCC --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-black text-gray-800 text-xs uppercase tracking-widest">Cronograma de Acompanhamento (TCC)</h3>
                        @if($acompanhamento)
                            <span class="bg-green-100 text-green-700 text-[9px] font-black px-2 py-1 rounded-full uppercase">Ativo</span>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        @if(!$acompanhamento)
                            <div class="text-center py-8">
                                <p class="text-xs text-gray-400 italic font-medium">Nenhum plano de acompanhamento gerado para o seu TCC até o momento.</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($acompanhamento->fases()->take(2)->get() as $fase)
                                    <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                        <div class="flex-1">
                                            <h4 class="text-xs font-black text-gray-800 uppercase">{{ $fase->fase }}</h4>
                                            <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">
                                                Prazo: {{ $acompanhamento->mes_referencia ? \Carbon\Carbon::parse($acompanhamento->mes_referencia)->addMonths($fase->mes_fim - 1)->translatedFormat('F/Y') : 'A definir' }}
                                            </p>
                                        </div>
                                        <span class="text-[9px] font-black uppercase px-3 py-1 rounded-lg bg-white border border-gray-200 text-gray-600">
                                            {{ $fase->status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- SEÇÃO 3: CAMPANHAS EM QUE ESTÁ --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-100">
                        <h3 class="font-black text-gray-800 text-xs uppercase tracking-widest">Minhas Campanhas</h3>
                    </div>
                    <div class="p-6">
                        @forelse($estudante->campanhas()->take(2)->get() as $campanha)
                            <div class="p-4 border border-indigo-50 bg-indigo-50/30 rounded-2xl mb-4 last:mb-0">
                                <h4 class="text-xs font-black text-indigo-900 uppercase leading-tight">{{ $campanha->nome }}</h4>
                                <div class="flex justify-between items-center mt-3">
                                    <span class="text-[9px] font-bold text-indigo-400 uppercase italic">{{ $campanha->ano_academico }}</span>
                                    <span class="text-[10px] font-black text-indigo-600 uppercase">Inscrito</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-xs text-gray-400 italic py-4">Você não está vinculado a nenhuma campanha.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- SIDEBAR: NOTIFICAÇÕES, ANTEPROJETOS E STATUS --}}
            <div class="space-y-6">
                
                {{-- 🔥 NOVO BLOCO: DUAS ÚLTIMAS NOTIFICAÇÕES --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alertas Recentes</h4>
                        <a href="{{ route('notifications.index') }}" class="text-[9px] font-black uppercase tracking-tight text-indigo-600 hover:text-indigo-800 transition-colors">
                            Ver Todas →
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse(Auth::user()->notifications()->take(2)->get() as $notification)
                            @php $isUnread = is_null($notification->read_at); @endphp
                            <div class="p-3.5 rounded-2xl border transition-all relative overflow-hidden {{ $isUnread ? 'bg-indigo-50/40 border-indigo-100/70' : 'bg-gray-50/60 border-gray-100' }}">
                                
                                {{-- Indicador luminoso visual se não estiver lida --}}
                                @if($isUnread)
                                    <span class="absolute top-3.5 right-3.5 flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                    </span>
                                @endif

                                <div class="pr-4">
                                    <span class="text-[8px] font-black uppercase tracking-wider text-gray-400 block mb-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    {{-- O link interceptor para ler e redirecionar automaticamente --}}
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="block group">
                                        <h5 class="text-[11px] font-black text-gray-800 uppercase leading-tight group-hover:text-indigo-600 transition-colors">
                                            {{ $notification->data['titulo'] ?? 'Aviso do Sistema' }}
                                        </h5>
                                        <p class="text-[10px] text-gray-500 mt-1 leading-relaxed line-clamp-2">
                                            {{ $notification->data['mensagem'] ?? '' }}
                                        </p>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 border border-dashed border-gray-200 rounded-2xl">
                                <p class="text-[11px] text-gray-400 italic">Nenhuma notificação registada.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ANTEPROJETOS RECENTES --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase mb-6 tracking-widest">Anteprojetos Recentes</h4>
                    @forelse($estudante->anteprojetos()->take(3)->get() as $anteprojeto)
                        <div class="mb-4 p-3 border-l-2 border-indigo-500 bg-gray-50 rounded-r-xl">
                            <h5 class="text-[11px] font-black text-gray-800 uppercase leading-tight">{{ $anteprojeto->titulo }}</h5>
                            <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase">{{ $anteprojeto->campanha->nome ?? 'S/ Campanha' }}</p>
                            <div class="mt-2 text-right">
                                <span class="text-[9px] font-black text-indigo-600 uppercase">{{ $anteprojeto->status }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic">Nenhum anteprojeto submetido.</p>
                    @endforelse
                </div>

             
            </div>

        </div>
    </div>
</main>
@endsection