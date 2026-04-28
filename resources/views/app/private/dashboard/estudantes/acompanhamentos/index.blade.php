@extends('app.private.dashboard.app..templete')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="mb-10">
        <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter italic">Meus Acompanhamentos</h1>
        <p class="text-gray-500 font-medium">Gerencie o progresso das suas orientações de TCC.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($acompanhamentos as $acomp)
        <div class="bg-white border-4 border-gray-900 p-8 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
            <div class="flex justify-between items-start mb-6">
                <span class="text-[10px] font-black bg-blue-600 text-white px-3 py-1 uppercase tracking-widest">Ativo</span>
                <span class="text-xs font-bold text-gray-400 italic">#{{ $acomp->id }}</span>
            </div>

            <h2 class="text-2xl font-black text-gray-900 uppercase leading-none mb-2">TCC - Orientação</h2>
            <p class="text-sm text-gray-600 mb-6 font-medium">Tutor: <span class="text-gray-900 font-black underline">{{ $acomp->tutor->professor->user->name }}</span></p>

            <div class="border-t-2 border-gray-100 pt-6 flex justify-between items-center">
                <div class="text-left">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Início do Processo</p>
                    <p class="text-sm font-bold text-gray-800">{{ $acomp->created_at->format('d/m/Y') }}</p>
                </div>
                <a href="{{ route('estudante.acompanhamento.show', $acomp->id) }}" 
                   class="px-6 py-3 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-colors">
                    Ver Cronograma
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection