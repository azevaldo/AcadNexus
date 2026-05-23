@extends('app.private.dashboard.app..templete')

@section('title','Editar Estudante')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Título -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Orientações e tutoria
        </h1>
        <p class="text-gray-500 text-sm">
           Informações sobre orientação e tutoria do estudante
        </p>
    </div>

    <!-- Card -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border">

        <form method="POST"
            action="{{ route('estudantes.update',$estudante->id) }}">

            @csrf
            @method('PUT')

            <div class="p-8 space-y-8">

                <!-- Seção Usuário -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">
                        Informações do Usuário
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="text-sm text-gray-600">
                                Nome
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ $estudante->user->name }}"
                                class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                readonly>
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">
                                Email
                            </label>

                            <input type="email"
                                name="email"
                                value="{{ $estudante->user->email }}"
                                class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                required readonly>
                        </div>

                    </div>
                </div>

                 </form>

{{-- Seção de Tutoria --}}
<div class="p-8 border-t bg-gray-50/50 rounded-b-2xl">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Orientação e Tutoria</h2>
            <p class="text-xs text-gray-500">Gerencie os professores responsáveis pelo acompanhamento.</p>
        </div>
        
        <button type="button" id="btnAbrirModal"
            class="px-4 py-2 {{ $estudante->tutorRelacao ? 'bg-amber-500 hover:bg-amber-600' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
            <i class="fas {{ $estudante->tutorRelacao ? 'fa-edit' : 'fa-plus' }}"></i>
            {{ $estudante->tutorRelacao ? 'Alterar Orientadores' : 'Atribuir Orientadores' }}
        </button>
    </div>

 @if($estudante->tutorRelacao)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <div class="p-4 bg-white rounded-xl border border-blue-100 shadow-sm flex justify-between items-start">
            <div>
                <p class="text-[10px] uppercase font-black text-blue-500 tracking-widest mb-1">Tutor Principal</p>
                <p class="text-sm font-bold text-gray-700">{{ $estudante->tutorRelacao->tutor->user->name }}</p>
            </div>
            
            <form action="{{ route('estudantes.tutores.desvincular', $estudante->id) }}" method="POST" onsubmit="return confirm('Remover o tutor principal excluirá toda a orientação. Continuar?')">
                @csrf
                @method('DELETE')
                <input type="hidden" name="tipo" value="tutor">
                <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </form>
        </div>

        @if($estudante->tutorRelacao->coTutor)
        <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm flex justify-between items-start">
            <div>
                <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">Co-Tutor</p>
                <p class="text-sm font-bold text-gray-700">{{ $estudante->tutorRelacao->coTutor->user->name }}</p>
            </div>

            <form action="{{ route('estudantes.tutores.desvincular', $estudante->id) }}" method="POST" onsubmit="return confirm('Remover apenas o co-tutor?')">
                @csrf
                @method('DELETE')
                <input type="hidden" name="tipo" value="co_tutor">
                <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </form>
        </div>
        @endif
    </div>
@else
    <div class="text-center py-6 border-2 border-dashed border-gray-200 rounded-2xl">
        <p class="text-gray-400 text-sm italic">Nenhum orientador atribuído ainda.</p>
    </div>
@endif

    <div id="modalTutoria" class="fixed inset-0 z- hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl z- w-full max-w-md relative">
                <form action="{{ route('estudantes.tutores.vincular', $estudante->id) }}" method="POST">
                    @csrf
                    <div class="p-8 border-b flex justify-between items-center">
                        <h3 class="text-xl font-black text-gray-800 tracking-tight">Definir Orientadores</h3>
                        <button type="button" class="btnFecharModal text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="p-8 space-y-6">
                        <div>
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-2">Tutor Principal</label>
                            <select name="tutor_id" required class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 appearance-none bg-gray-50">
                                <option value="">Selecione um Tutor</option>
                                @foreach($tutores as $tutor)
                                    <option value="{{ $tutor->id }}" @selected($estudante->tutorRelacao?->tutor_id == $tutor->id)>
                                        {{ $tutor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-2">Co-Tutor (Opcional)</label>
                            <select name="co_tutor_id" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 appearance-none bg-gray-50">
                                <option value="">Nenhum</option>
                                @foreach($tutores as $tutor)
                                    <option value="{{ $tutor->id }}" @selected($estudante->tutorRelacao?->co_tutor_id == $tutor->id)>
                                        {{ $tutor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="p-8 bg-gray-50 flex flex-col gap-3">
                        <button type="submit" class="w-full py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                            Confirmar Atribuição
                        </button>
                        <button type="button" class="btnFecharModal w-full py-2 text-sm text-gray-400 font-bold">
                            Cancelar e Sair
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- SCRIPT PARA FUNCIONAMENTO --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalTutoria');
        const btnAbrir = document.getElementById('btnAbrirModal');
        const btnsFechar = document.querySelectorAll('.btnFecharModal');

        btnAbrir.onclick = () => modal.classList.remove('hidden');
        
        btnsFechar.forEach(btn => {
            btn.onclick = () => modal.classList.add('hidden');
        });

        // Fechar ao clicar fora do modal
        window.onclick = (event) => {
            if (event.target.classList.contains('bg-black/60')) {
                modal.classList.add('hidden');
            }
        };
    });
</script>
</div>

@endsection