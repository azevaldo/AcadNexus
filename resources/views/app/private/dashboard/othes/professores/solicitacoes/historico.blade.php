@extends('app.private.dashboard.app.templete')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 uppercase">Pedidos de Orientação</h1>
        <p class="text-gray-500 font-bold uppercase text-xs">Gerencie as solicitações enviadas pelos estudantes</p>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($solicitacoes as $s)
        <div class="bg-white border-2 border-gray-900 p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-4 flex-1">
                <div class="w-14 h-14 bg-gray-900 text-white flex items-center justify-center text-xl font-black">
                    {{ substr($s->estudante->user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-black text-gray-900 uppercase">{{ $s->estudante->user->name }}</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Curso: {{ $s->estudante->curso->nome ?? 'N/A' }} | Solicitado em: {{ $s->created_at->format('d/m/Y') }}</p>
                    <div class="mt-1">
                        <span class="text-[9px] font-black uppercase px-2 py-0.5 border border-gray-900 bg-blue-50 text-blue-600">
                            Pretende: {{ $s->tipo == 'tutor' ? 'Orientador Principal' : 'Co-Orientador' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                   <a href="{{route('estudantes.show',$s->estudante->id)}}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 border-2 border-gray-900 font-black uppercase text-xs shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all active:translate-y-1 active:shadow-none">
                        Info
                    </a>
                @if($s->status == 'pendente')
                <form action="{{ route('solicitacoes.responder', $s->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="aceite">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 border-2 border-gray-900 font-black uppercase text-xs shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all active:translate-y-1 active:shadow-none">
                        Aceitar Aluno
                    </button>
                </form>

                <form action="{{ route('solicitacoes.responder', $s->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="recusado">
                    <button type="submit" class="bg-white hover:bg-red-50 text-red-600 px-6 py-3 border-2 border-gray-900 font-black uppercase text-xs shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all active:translate-y-1 active:shadow-none">
                        Recusar
                    </button>
                </form>
                @else
                <span class="text-[10px] font-black uppercase text-gray-400 italic">Solicitação já Finalizada ({{ $s->status }})</span>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-gray-50 border-2 border-dashed border-gray-300 p-20 text-center">
            <p class="font-black text-gray-400 uppercase">Nenhum pedido pendente no momento.</p>
        </div>
        @endforelse
    </div>
     @if($solicitacoes->hasPages())
        <div class="px-6 py-5 bg-slate-50/50 border-t border-slate-100">
            {{ $solicitacoes->links() }}
        </div>
        @endif
</div>
@endsection