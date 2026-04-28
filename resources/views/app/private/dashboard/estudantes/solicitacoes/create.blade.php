@extends('app.private.dashboard.app.templete')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 uppercase">Solicitar Orientação</h1>
        <p class="text-gray-500 font-bold uppercase text-xs tracking-widest">Escolha um professor disponível para enviar seu pedido</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tutores as $tutor)
        <div class="bg-white border-2 border-gray-900 p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 border-2 border-gray-900 flex items-center justify-center font-black text-blue-600">
                        {{ substr($tutor->professor->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 uppercase leading-tight">{{ $tutor->professor->user->name }}</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $tutor->professor->area_especializacao ?? 'Área não definida' }}</p>
                    </div>
                </div>
                
                <div class="space-y-2 mb-6">
                    <div class="flex justify-between text-[10px] font-black uppercase">
                        <span class="text-gray-400">Vagas Ocupadas:</span>
                        <span class="text-gray-900">{{ $tutor->professor->estudantes_count ?? 0 }} / 5</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 border border-gray-900">
                        <div class="bg-blue-500 h-full" style="width: {{ ($tutor->professor->estudantes_count ?? 0) * 20 }}%"></div>
                    </div>
                </div>
            </div>

            <form action="{{ route('solicitacoes.store', $tutor->professor_id) }}" method="POST">
                @csrf
                <div class="flex flex-col gap-3">
                    <select name="tipo" class="w-full bg-gray-50 border-2 border-gray-900 p-2 text-[10px] font-black uppercase outline-none focus:bg-white">
                        <option value="tutor">Solicitar como Tutor Principal</option>
                        <option value="co_tutor">Solicitar como Co-Tutor</option>
                    </select>
                    <button type="submit" class="w-full bg-gray-900 text-white py-3 text-xs font-black uppercase hover:bg-blue-600 transition-colors shadow-[4px_4px_0px_0px_rgba(0,0,0,0.3)] active:shadow-none active:translate-y-1">
                        Enviar Solicitação
                    </button>
                </div>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection