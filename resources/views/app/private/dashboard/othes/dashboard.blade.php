@extends('app.private.dashboard.app.templete')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden bg-gray-50/50">
    {{-- Header --}}
    <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm">
        <div class="flex items-center bg-gray-100 rounded-2xl px-4 py-2 w-96">
            <i class="fas fa-search text-gray-400"></i>
            <input type="text" placeholder="Pesquisar..." class="bg-transparent border-none focus:ring-0 text-sm ml-2 w-full">
        </div>
        
        <div class="flex items-center space-x-6">
            <div class="text-right">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ Auth::user()->roles->first()->name ?? 'Utilizador' }}</p>
                <p class="text-sm font-bold text-gray-700">{{ now()->translatedFormat('d \d\e F, Y') }}</p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                <i class="fas fa-user-shield"></i>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 space-y-8">
        
        {{-- Grid de Stats (Ajustado para 3 colunas) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm transition-transform hover:scale-[1.02]">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">TCCs em Curso</p>
                <h4 class="text-3xl font-black text-gray-800 mt-2">{{ $stats['tccs_em_curso'] }}</h4>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm transition-transform hover:scale-[1.02]">
                <p class="text-[10px] font-black text-yellow-500 uppercase tracking-widest">Pendentes de Avaliação</p>
                <h4 class="text-3xl font-black text-gray-800 mt-2">{{ $stats['pendentes_avaliacao'] }}</h4>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm transition-transform hover:scale-[1.02]">
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Campanhas Abertas</p>
                <h4 class="text-3xl font-black text-gray-800 mt-2">{{ $stats['campanhas_ativas'] }}</h4>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Coluna Principal --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/20">
                        <h3 class="font-black text-gray-800 text-lg uppercase tracking-tighter">
                            {{ Auth::user()->hasRole('coordenador') ? 'Submissões Recentes' : 'Meus Orientados' }}
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-widest">
                                <tr>
                                    <th class="px-8 py-4">Estudante</th>
                                    <th class="px-8 py-4">Tema do Anteprojeto</th>
                                    <th class="px-8 py-4 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($recentes as $item)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-[10px] mr-3">
                                                {{ strtoupper(substr($item->estudante->user->name ?? 'EE', 0, 2)) }}
                                            </div>
                                            <p class="text-sm font-black text-gray-800">{{ $item->estudante->user->name ?? 'Estudante Sem Nome' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-xs font-bold text-gray-600 truncate w-64">{{ $item->titulo }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <button class="p-2 text-indigo-400 hover:text-indigo-700 transition">
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center text-gray-400 text-xs italic">Nenhuma submissão recente.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Sidebar (Coluna Lateral Direita) --}}
            <div class="space-y-6">
                
                {{-- 🔥 NOVO BLOCO: ALERTAS RECENTES (2 ÚLTIMAS NOTIFICAÇÕES) --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="font-black text-xs uppercase tracking-widest text-gray-400 flex items-center">
                            <i class="fas fa-bell mr-2 text-indigo-500"></i> Alertas Recentes
                        </h4>
                        <a href="{{ route('notifications.index') }}" class="text-[9px] font-black uppercase tracking-tight text-indigo-600 hover:text-indigo-800 transition-colors">
                            Ver Todas →
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse(Auth::user()->notifications()->take(2)->get() as $notification)
                            @php $isUnread = is_null($notification->read_at); @endphp
                            <div class="p-4 rounded-2xl border transition-all relative overflow-hidden {{ $isUnread ? 'bg-indigo-50/40 border-indigo-100/70' : 'bg-gray-50/60 border-gray-100' }}">
                                
                                {{-- Indicador visual de notificação não lida --}}
                                @if($isUnread)
                                    <span class="absolute top-4 right-4 flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                    </span>
                                @endif

                                <div class="pr-3">
                                    <span class="text-[8px] font-black uppercase tracking-wider text-gray-400 block mb-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    
                                    {{-- Link de Redirecionamento Inteligente com Interceptor --}}
                                    <a href="{{ route('notifications.redirect', ['id' => $notification->id, 'target' => $notification->data['target'] ?? '', 'target_id' => $notification->data['target_id'] ?? '']) }}" class="block group">
                                        <h5 class="text-[11px] font-black text-gray-800 uppercase leading-tight group-hover:text-indigo-600 transition-colors">
                                            {{ $notification->data['titulo'] ?? 'Aviso Geral' }}
                                        </h5>
                                        <p class="text-[10px] text-gray-500 mt-1 leading-relaxed line-clamp-2">
                                            {{ $notification->data['mensagem'] ?? '' }}
                                        </p>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 border border-dashed border-gray-200 rounded-2xl">
                                <p class="text-[10px] text-gray-400 italic">Nenhum aviso registado.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Gestão Admin (Visível apenas para Coordenadores) --}}
                @if(Auth::user()->hasRole('coordenador'))
                <div class="bg-gray-900 text-white p-8 rounded-[2.5rem] shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h4 class="font-black text-xl mb-4 uppercase tracking-tighter italic">Gestão Admin</h4>
                        <div class="space-y-3">
                            <button class="w-full bg-indigo-600 hover:bg-indigo-500 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition">
                                <i class="fas fa-plus-circle mr-2"></i> Criar Campanha
                            </button>
                            <button class="w-full bg-white/10 hover:bg-white/20 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-white/5 transition">
                                <i class="fas fa-users mr-2"></i> Membros
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Campanhas Ativas --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                    <h4 class="font-black text-xs uppercase tracking-widest text-gray-400 mb-6 flex items-center">
                        <i class="fas fa-bullhorn mr-2"></i> Campanhas Ativas
                    </h4>
                    
                    <div class="space-y-6">
                        @forelse($campanhas as $camp)
                        <div class="group">
                            <div class="flex justify-between items-end mb-2">
                                <div>
                                    <p class="text-[10px] font-black text-gray-800 uppercase">{{ $camp->titulo }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold">Prazo: {{ \Carbon\Carbon::parse($camp->prazo_submissao)->format('d/m/Y') }}</p>
                                </div>
                                <span class="text-[10px] font-black {{ $camp->dias_restantes < 7 ? 'text-red-500' : 'text-indigo-600' }}">
                                    {{ $camp->dias_restantes }} dias
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-full transition-all duration-500" style="width: {{ $camp->progresso }}%"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-[10px] text-gray-400 italic">Nenhuma campanha aberta no momento.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection