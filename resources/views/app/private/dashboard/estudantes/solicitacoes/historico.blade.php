@extends('app.private.dashboard.app.templete')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 uppercase">Minhas Solicitações</h1>
            <p class="text-gray-500 font-bold uppercase text-xs">Acompanhe o status dos seus pedidos de orientação</p>
        </div>
        <a href="{{ route('solicitacoes.create') }}" class="bg-blue-600 text-white px-6 py-3 border-2 border-gray-900 font-black uppercase text-xs shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            Nova Solicitação
        </a>
    </div>

    <div class="bg-white border-2 border-gray-900 shadow-[8px_8px_0px_0px_rgba(0,0,0,0.05)] overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-900 text-white text-[10px] uppercase font-black tracking-widest">
                    <th class="p-4">Professor</th>
                    <th class="p-4">Tipo</th>
                    <th class="p-4">Data</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($solicitacoes as $s)
                <tr class="border-b-2 border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="p-4">
                        <p class="font-black text-gray-900 uppercase text-sm">{{ $s->professor->user->name }}</p>
                    </td>
                    <td class="p-4">
                        <span class="text-[10px] font-black uppercase px-2 py-1 border border-gray-900 {{ $s->tipo == 'tutor' ? 'bg-blue-100' : 'bg-purple-100' }}">
                            {{ $s->tipo }}
                        </span>
                    </td>
                    <td class="p-4 text-xs font-bold text-gray-500">{{ $s->created_at->format('d/m/Y') }}</td>
                    <td class="p-4">
                        @php
                            $cores = [
                                'pendente' => 'bg-amber-400',
                                'aceite' => 'bg-green-400',
                                'recusado' => 'bg-red-400'
                            ];
                        @endphp
                        <span class="{{ $cores[$s->status] }} border-2 border-gray-900 px-3 py-1 text-[9px] font-black uppercase shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]">
                            {{ $s->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-10 text-center font-bold text-gray-400 uppercase italic">Nenhuma solicitação enviada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection