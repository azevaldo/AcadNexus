@extends('app.private.dashboard.app..templete')

@section('title', 'Meus Orientandos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-10">
        <h1 class="text-3xl font-black text-gray-800 tracking-tight">Meus Orientandos</h1>
        <p class="text-gray-500 mt-1">Estudantes sob sua responsabilidade técnica e pedagógica.</p>
    </div>

    <div class="bg-white shadow-2xl rounded-[2rem] overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Estudante</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Curso & Matrícula</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Seu Papel</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Parceria</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Acompanhamento</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orientandos as $relacao)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-11 w-11 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-100 group-hover:scale-110 transition-transform">
                                    {{ substr($relacao->estudante->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $relacao->estudante->user->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-medium">{{ $relacao->estudante->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-sm font-semibold text-gray-700">{{ $relacao->estudante->curso->nome }}</p>
                            <p class="text-[11px] text-gray-400">{{ $relacao->estudante->numero_matricula }}</p>
                        </td>
                        <td class="px-8 py-6">
                            @if($relacao->tutor_id == $tutor->id)
                                <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-xl text-[10px] font-black uppercase tracking-wider">
                                    Tutor Principal
                                </span>
                            @else
                                <span class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-xl text-[10px] font-black uppercase tracking-wider">
                                    Co-Tutor
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-sm text-gray-500 font-medium">
                            @if($relacao->tutor_id == $tutor->id)
                                {{ $relacao->coTutor ? $relacao->coTutor->user->name : 'Nenhum co-tutor' }}
                            @else
                                {{ $relacao->tutor->user->name }} (Principal)
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            @if($relacao->estudante->acompanhamento)
                            <a href="{{route('tutor.acompanhamento.show',$relacao->estudante->acompanhamento)}}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition-colors shadow-md">
                                <i class="fas fa-project-diagram"></i>
                                Ver TCC
                            </a>
                            @endif

                            @if(!$relacao->estudante->acompanhamento)
                            <a href="{{route('tutor.acompanhamento.confirm',$relacao->estudante)}}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white text-[11px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition-colors shadow-md">
                                <i class="fas fa-project-diagram"></i>
                                Iniciar
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user-graduate text-gray-300 text-2xl"></i>
                                </div>
                                <h4 class="text-gray-500 font-bold">Nenhum estudante vinculado</h4>
                                <p class="text-gray-400 text-sm">Você ainda não possui orientações atribuídas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection