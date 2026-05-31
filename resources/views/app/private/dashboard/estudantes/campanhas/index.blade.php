@extends('app.private.dashboard.app.templete')
@section('title', 'Gestão de Campanhas')

@section('content')

<div class="p-6 md:p-10 max-w-7xl mx-auto space-y-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                Campanhas Académicas
            </h1>
            <p class="text-slate-500 text-sm mt-1 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Gerencie os períodos de submissão de anteprojetos por curso.
            </p>
        </div>

        
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 text-xs uppercase tracking-wider font-bold text-slate-500">Título da Campanha</th>
                        <th class="px-6 py-5 text-xs uppercase tracking-wider font-bold text-slate-500">Curso Associado</th>
                        <th class="px-6 py-5 text-xs uppercase tracking-wider font-bold text-slate-500">Estado</th>
                        <th class="px-6 py-5 text-xs uppercase tracking-wider font-bold text-slate-500 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">
                    @forelse($campanhas as $campanha)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mr-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-bullhorn text-sm"></i>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800">{{ $campanha->titulo }}</span>
                                    <span class="text-[11px] text-slate-400 uppercase font-medium">Ref: #{{ str_pad($campanha->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-slate-600">{{ $campanha->curso->nome ?? 'Sem Curso' }}</span>
                                <span class="text-xs text-slate-400">{{ $campanha->curso->departamento->nome ?? 'N/A' }}</span>
                            </div>
                        </td>

                       <td class="px-6 py-5">
                            @if($campanha->status == 'aberta' || $campanha->status == 'Ativa')
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold uppercase tracking-tight">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                                    Ativa
                                </span>
                            @elseif($campanha->status == 'encerrada')
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold uppercase tracking-tight">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-2"></span>
                                    Encerrada
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-50 text-red-600 text-xs font-bold uppercase tracking-tight border border-red-100">
    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
    Cancelada
</span>
                                
                            @endif
                        </td>


                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end items-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('estudante.campanhaestudante.show', $campanha->id) }}" 
                                   class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Ver Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>

                            </div>
                        </td>
                    </tr>


                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-folder-open text-4xl text-slate-200 mb-4"></i>
                                <p class="text-slate-400 font-medium">Nenhuma campanha registada até ao momento.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($campanhas->hasPages())
        <div class="px-6 py-5 bg-slate-50/50 border-t border-slate-100">
            {{ $campanhas->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function openModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden'; // Evita scroll ao abrir
}

function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Fechar modal ao clicar fora dele
window.onclick = function(event) {
    if (event.target.classList.contains('fixed')) {
        event.target.classList.add('hidden');
        event.target.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}
</script>

@endsection