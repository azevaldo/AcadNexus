@extends('app.private.dashboard.app.templete')

@section('title', 'Central de Notificações')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 font-sans">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Central de Notificações</h1>
            <p class="text-sm text-gray-500 mt-1">Gerencie os alertas e avisos emitidos automaticamente pelo sistema de TCC.</p>
        </div>
        @if(Auth::user()->unreadNotifications->isNotEmpty())
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2.5 rounded-xl transition-all">
                    Marcar Todas como Lidas
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($notificacoes as $notif)
                @php $isUnread = is_null($notif->read_at); @endphp
                <div class="p-6 transition-all flex items-start justify-between gap-4 {{ $isUnread ? 'bg-indigo-50/30 font-medium' : 'bg-white' }}">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            {{-- Badge Dinâmica baseada no tipo --}}
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md 
                                {{ in_array($notif->data['tipo'] ?? '', ['anteprojeto_submissao', 'acompanhamento_submissao']) ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                {{ str_replace('_', ' ', $notif->data['tipo'] ?? 'Sistema') }}
                            </span>
                            <span class="text-[11px] text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        
                        {{-- Título clicável que executa a leitura e redirecionamento automático --}}
                        <a href="{{ $notif->data['url'] ?? '#' }}" class="block group">
                            <h4 class="text-sm font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">
                                {{ $notif->data['titulo'] }}
                            </h4>
                            <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">
                                {{ $notif->data['mensagem'] }}
                            </p>
                        </a>
                    </div>

                    {{-- Botão lateral para Alternar estado entre Lido e Não Lido individualmente --}}
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('notifications.toggle', $notif->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" title="{{ $isUnread ? 'Marcar como lida' : 'Marcar como não lida' }}"
                                    class="p-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-400 hover:text-gray-700 transition-all shadow-2xs">
                                @if($isUnread)
                                    <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <p class="text-sm font-semibold text-gray-400 italic">Você não possui nenhuma notificação no momento.</p>
                </div>
            @endforelse
        </div>
        
        @if($notificacoes->hasPages())
            <div class="p-4 bg-gray-50/50 border-t border-gray-100">
                {{ $notificacoes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection