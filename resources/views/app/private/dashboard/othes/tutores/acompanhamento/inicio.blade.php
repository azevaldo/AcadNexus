 {{-- app/private/dashboard/othes/tutores/acompanhamento/confirm_start.blade.php --}}
@extends('app.private.dashboard.app.templete')

@section('content')
<div class="max-w-2xl mx-auto py-12 sm:py-20 px-4">
    <div class="bg-white rounded-[2.5rem] p-8 sm:p-12 shadow-2xl border border-gray-100 text-center relative overflow-hidden">
        
        {{-- Botão Voltar Superior --}}
        <a href="{{ route('tutor.estudantes.index') }}" 
           class="absolute top-6 left-6 flex items-center justify-center w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100 hover:border-slate-300 transition-all shadow-sm"
           title="Voltar para Estudantes">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        {{-- Ícone Animado --}}
        <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6 mt-4 sm:mt-2">
            <i class="fas fa-rocket text-3xl"></i>
        </div>
        
        <h2 class="text-2xl font-black text-gray-800 uppercase italic tracking-wide">Iniciar Acompanhamento</h2>
        <p class="text-gray-500 mt-2 text-sm">Você está prestes a iniciar o processo de TCC para:</p>
        <p class="text-xl font-extrabold text-indigo-600 mt-1">{{ $estudante->user->name }}</p>

        {{-- Formulário de Ação --}}
        <form action="{{ route('tutor.acompanhamento.storeBasic', $estudante->id) }}" method="POST" class="mt-8">
            @csrf
            <input type="hidden" name="tutor_id" value="{{ $vinculo->tutor_id }}">
            <input type="hidden" name="co_tutor_id" value="{{ $vinculo->co_tutor_id }}">
            <input type="hidden" name="anteprojeto_id" value="{{ $anteprojeto->id }}">

            <div class="flex flex-col gap-4 max-w-sm mx-auto">
                <button type="submit" class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-indigo-600 transition-all uppercase tracking-widest text-xs shadow-md active:scale-[0.99]">
                    Confirmar e Iniciar Agora
                </button>
                
                {{-- Cancelar redirecionando explicitamente para a listagem --}}
                <a href="{{ route('tutor.estudantes.index') }}" 
                   class="text-xs font-bold text-slate-400 uppercase tracking-wider hover:text-red-500 transition-colors py-1">
                    Cancelar e Voltar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection