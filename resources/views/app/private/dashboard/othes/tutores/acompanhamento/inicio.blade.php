{{-- app/private/dashboard/othes/tutores/acompanhamento/confirm_start.blade.php --}}
@extends('app.private.dashboard.app.templete')

@section('content')
<div class="max-w-2xl mx-auto py-20">
    <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl border border-gray-100 text-center">
        <div class="w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-rocket text-3xl"></i>
        </div>
        
        <h2 class="text-2xl font-black text-gray-800 uppercase italic">Iniciar Acompanhamento</h2>
        <p class="text-gray-500 mt-2">Você está prestes a iniciar o processo de TCC para:</p>
        <p class="text-lg font-bold text-indigo-600">{{ $estudante->user->name }}</p>

        <form action="{{ route('tutor.acompanhamento.storeBasic', $estudante->id) }}" method="POST" class="mt-8">
            @csrf
            <input type="hidden" name="tutor_id" value="{{ $vinculo->tutor_id }}">
            <input type="hidden" name="co_tutor_id" value="{{ $vinculo->co_tutor_id }}">
            <input type="hidden" name="anteprojeto_id" value="{{ $anteprojeto->id }}">

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full py-4 bg-gray-900 text-white font-black rounded-2xl hover:bg-indigo-600 transition uppercase tracking-widest text-sm">
                    Confirmar e Iniciar Agora
                </button>
                <a href="{{ url()->previous() }}" class="text-xs font-bold text-gray-400 uppercase hover:text-red-500">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection