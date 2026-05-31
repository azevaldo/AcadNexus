@extends('app.private.dashboard.app..templete')

@section('title','Editar Estudante')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Orientações e tutoria
        </h1>
        <p class="text-gray-500 text-sm">
           Informações sobre orientação e tutoria do estudante
        </p>
    </div>

    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border">

        <form method="POST" action="{{ route('estudantes.update',$estudante->id) }}">
            @csrf
            @method('PUT')

            <div class="p-8 space-y-8">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">
                        Informações do Usuário
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm text-gray-600">Nome</label>
                            <input type="text" name="name" value="{{ $estudante->user->name }}" class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" readonly>
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Email</label>
                            <input type="email" name="email" value="{{ $estudante->user->email }}" class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required readonly>
                        </div>
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
                
                <button type="button" id="btnAbrirModal" class="px-4 py-2 {{ $estudante->tutorRelacao ? 'bg-amber-500 hover:bg-amber-600' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white text-xs font-bold rounded-xl shadow-lg transition-all flex items-center gap-2">
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
                        
                        <button onclick="document.getElementById('modal-desvincular-tutor').classList.remove('hidden')" type="button" class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Remover Tutor Principal">
    <i class="fas fa-trash-alt text-xs"></i>
</button>

<div id="modal-desvincular-tutor" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border-2 border-gray-900 rounded-2xl max-w-sm w-full p-6 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
        
        <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight text-red-600">Excluir Orientação?</h3>
        <p class="text-xs text-gray-500 mt-2 font-medium">Remover o tutor principal excluirá toda a orientação deste estudante. Deseja continuar?</p>
        
        <div class="mt-6 flex justify-end gap-2">
            <button onclick="document.getElementById('modal-desvincular-tutor').classList.add('hidden')" type="button" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase rounded-xl transition-colors">
                Cancelar
            </button>
            
            <form action="{{ route('estudantes.tutores.desvincular', $estudante->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="tipo" value="tutor">
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase rounded-xl transition-colors">
                    Excluir Tudo
                </button>
            </form>
        </div>
    </div>
</div>
                    </div>

                    @if($estudante->tutorRelacao->coTutor)
                        <div class="p-4 bg-white rounded-xl border border-gray-100 shadow-sm flex justify-between items-start">
                            <div>
                                <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">Co-Tutor</p>
                                <p class="text-sm font-bold text-gray-700">{{ $estudante->tutorRelacao->coTutor->user->name }}</p>
                            </div>

                           <button onclick="document.getElementById('modal-desvincular').classList.remove('hidden')" class="inline-flex items-center justify-center p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Remover Co-Tutor">
    <i class="fas fa-trash-alt text-xs"></i>
</button>

<div id="modal-desvincular" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white border-2 border-gray-900 rounded-2xl max-w-sm w-full p-6 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]">
        
        <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight text-red-600">Confirmar Remoção</h3>
        <p class="text-xs text-gray-500 mt-2 font-medium">Tem certeza que deseja remover apenas o co-tutor deste estudante?</p>
        
        <div class="mt-6 flex justify-end gap-2">
            <button onclick="document.getElementById('modal-desvincular').classList.add('hidden')" type="button" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold uppercase rounded-xl transition-colors">
                Cancelar
            </button>
            
            <form action="{{ route('estudantes.tutores.desvincular', $estudante->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="tipo" value="co_tutor">
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-black uppercase rounded-xl transition-colors">
                    Remover
                </button>
            </form>
        </div>
    </div>
</div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-6 border-2 border-dashed border-gray-200 rounded-2xl">
                    <p class="text-gray-400 text-sm italic">Nenhum orientador atribuído ainda.</p>
                </div>
            @endif
{{-- Modal --}}
<div id="modalTutoria" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl z-50 w-full max-w-md relative">
            <form action="{{ route('estudantes.tutores.vincular', $estudante->id) }}" method="POST">
                @csrf
                <div class="p-8 border-b flex justify-between items-center">
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">Definir Orientadores</h3>
                    <button type="button" class="btnFecharModal text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    {{-- Select do Tutor Principal (Possui a validação de limite) --}}
                    <div>
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-2">Tutor Principal</label>
                        <select name="tutor_id" required class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 appearance-none bg-gray-50 text-sm">
                            <option value="">Selecione um Tutor</option>
                            @foreach($tutores as $tutor)
                                @php
                                    $atingiuLimite = $tutor->orientacoes_ativas_count >= $tutor->max_orientacoes;
                                    $ehOTutorAtual = $estudante->tutorRelacao?->tutor_id == $tutor->id;
                                    $indisponivel = $atingiuLimite && !$ehOTutorAtual;
                                @endphp
                                
                                <option value="{{ $tutor->id }}" 
                                    @selected($ehOTutorAtual)
                                    @disabled($indisponivel)
                                    class="{{ $indisponivel ? 'text-gray-400 bg-gray-100' : 'text-gray-800' }}">
                                    
                                    {{ $tutor->user->name }} 
                                    
                                    @if($indisponivel)
                                        (Não Disponível)
                                    @else
                                        ({{ $tutor->orientacoes_ativas_count }}/{{ $tutor->max_orientacoes }} vagas)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select do Co-Tutor (Livre: sem bloqueios por limite de vagas) --}}
                    <div>
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-2">Co-Tutor (Opcional)</label>
                        <select name="co_tutor_id" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 appearance-none bg-gray-50 text-sm">
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
</div>    </div>
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

        window.onclick = (event) => {
            if (event.target.classList.contains('bg-black/60')) {
                modal.classList.add('hidden');
            }
        };
    });
</script>

@endsection