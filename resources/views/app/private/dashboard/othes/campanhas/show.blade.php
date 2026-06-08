 @extends('app.private.dashboard.app.templete')

@section('title', 'Detalhes da Campanha')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-10 lg:px-10">

     <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-12">
    {{-- Bloco do Título + Botão Voltar --}}
    <div class="flex items-start gap-4">
        {{-- Botão Voltar Compacto e Elegante --}}
        <a href="{{ route('campanhas.index') }}" 
           class="mt-1 flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm shrink-0"
           title="Voltar para Campanhas">
            <i class="fas fa-arrow-left"></i>
        </a>

        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                {{ $campanha->titulo }}
            </h1>
            <p class="text-slate-500 mt-2 flex items-center text-sm">
                <span class="w-6 h-px bg-slate-300 mr-3"></span>
                Painel Geral de Informações
            </p>
        </div>
    </div>

    {{-- Bloco de Ações (Buttons) --}}
    <div class="flex flex-wrap gap-3 w-full lg:w-auto justify-start lg:justify-end">
        @hasanyrole('admin|coordenador')
        @role('admin')
 <button onclick="openModal('prazosModal{{ $campanha->id }}')"
                class="bg-indigo-600 text-white hover:bg-indigo-700 px-5 py-3 rounded-xl font-bold transition shadow-md shadow-indigo-200 border border-transparent text-sm active:scale-[0.98]">
                <i class="fas fa-calendar-plus mr-2"></i> Ajustar Prazos
            </button>

            <a href="{{ route('campanhas.edit', $campanha->id) }}"
               class="bg-white text-slate-700 hover:bg-slate-50 px-5 py-3 rounded-xl font-bold transition shadow-sm border border-slate-200 text-sm">
                <i class="fas fa-edit mr-2 text-amber-500"></i> Editar Campanha
            </a>
        @endrole
           @if(auth()->user()->ehCoordenadorDoCurso($campanha->curso_id))
 <button onclick="openModal('prazosModal{{ $campanha->id }}')"
                class="bg-indigo-600 text-white hover:bg-indigo-700 px-5 py-3 rounded-xl font-bold transition shadow-md shadow-indigo-200 border border-transparent text-sm active:scale-[0.98]">
                <i class="fas fa-calendar-plus mr-2"></i> Ajustar Prazos
            </button>

            <a href="{{ route('campanhas.edit', $campanha->id) }}"
               class="bg-white text-slate-700 hover:bg-slate-50 px-5 py-3 rounded-xl font-bold transition shadow-sm border border-slate-200 text-sm">
                <i class="fas fa-edit mr-2 text-amber-500"></i> Editar Campanha
            </a>
           @endif
           
        @endhasanyrole
        
        <a href="{{ route('admin.campanhas.anteprojetos', $campanha->id) }}"
           class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-5 py-3 rounded-xl font-bold transition shadow-sm border border-indigo-100 text-sm">
            <i class="fas fa-file-alt mr-2"></i> Ver Anteprojetos
        </a>
        
        <a href="{{ route('campanhas.estudantes', $campanha->id) }}"
           class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-5 py-3 rounded-xl font-bold transition shadow-sm border border-indigo-100 text-sm">
            <i class="fas fa-users mr-2"></i> Ver Alunos
        </a>
    </div>
</div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                <h2 class="text-xs uppercase tracking-[0.2em] font-black text-slate-400 mb-8 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-blue-500 text-base"></i> Informações Gerais
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-6">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1">Curso</span>
                        <p class="text-lg font-bold text-slate-800 border-l-4 border-blue-500 pl-4 uppercase tracking-tight">
                            {{ $campanha->curso->nome }}
                        </p>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1">Coordenador</span>
                        <p class="text-lg font-bold text-slate-800 border-l-4 border-indigo-500 pl-4">
                            {{ $campanha->coordenador->professor->user->name }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-2">Estado Atual</span>
                        @if($campanha->status == 'aberta')
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-black bg-emerald-50 text-emerald-600 border border-emerald-100">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span> Aberta
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-black bg-slate-100 text-slate-500 border border-slate-200">
                                Encerrada
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-slate-50">
                    <h2 class="text-xs uppercase tracking-[0.2em] font-black text-slate-400 mb-4 tracking-wider">Descrição Detalhada</h2>
                    <div class="bg-slate-50/80 p-6 rounded-2xl text-slate-600 leading-relaxed italic border border-slate-100">
                        {{ $campanha->descricao }}
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl shadow-slate-200 relative overflow-hidden">
                <div class="flex justify-between items-center mb-8 relative z-10">
                    <h2 class="text-xs uppercase tracking-[0.2em] font-black text-slate-400">Cronograma</h2>
                    <i class="fas fa-clock text-slate-700 text-xl"></i>
                </div>
                
                <div class="space-y-8 relative z-10">
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-blue-400">
                                <i class="fas fa-upload"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase">Submissão</span>
                                <p class="font-bold text-slate-100">{{ ($campanha->prazo_submissao)? \Carbon\Carbon::parse($campanha->prazo_submissao)->format('d/m/Y'):'Não definido' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-amber-400">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase">Melhorias</span>
                                <p class="font-bold text-slate-100">{{ ($campanha->prazo_melhorias)?\Carbon\Carbon::parse($campanha->prazo_melhorias)->format('d/m/Y') :'Não definido'}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 p-6 space-y-4 shadow-sm">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-400 font-medium">Criado em</span>
                    <span class="text-slate-800 font-bold">{{ $campanha->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="h-px bg-slate-50"></div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-400 font-medium">Última atualização</span>
                    <span class="text-slate-800 font-bold">{{ $campanha->updated_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@hasanyrole('admin|coordenador')
<div id="prazosModal{{ $campanha->id }}" class="fixed inset-0 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm z- p-4">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl border border-slate-100 animate-fadeIn">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-extrabold text-slate-900">Configurar Prazos</h2>
            <button onclick="closeModal('prazosModal{{ $campanha->id }}')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('campanhas.updatePrazos', $campanha->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Prazo de Submissão</label>
                <div class="relative">
                    <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="date" name="prazo_submissao" value="{{ $campanha->prazo_submissao }}" required
                         class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all font-medium text-slate-700">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700">Prazo de Melhorias</label>
                <div class="relative">
                    <i class="fas fa-tools absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="date" name="prazo_melhorias" value="{{ $campanha->prazo_melhorias }}" 
                      min="{{ date('Y-m-d', strtotime('+1 day')) }}"   class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all font-medium text-slate-700">
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('prazosModal{{ $campanha->id }}')" 
                    class="flex-1 px-6 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">
                    Cancelar
                </button>
                <button type="submit" 
                    class="flex-1 px-6 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">
                    Confirmar Datas
                </button>
            </div>
        </form>
    </div>
</div>
@endhasanyrole

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.animate-fadeIn { animation: fadeIn 0.25s ease-out forwards; }
</style>

<script>
function openModal(id) {
    const modal = document.getElementById(id);
    if(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Fecha o modal ao clicar fora do conteúdo branco
window.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('[id^="prazosModal"]');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
});
</script>

@endsection