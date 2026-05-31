@extends('app.private.dashboard.app.templete')

@section('title', 'Estudantes Sob Acompanhamento')

@section('content')
<div class="p-8 max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <span class="bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border border-indigo-200">
                Portfólio de Orientação
            </span>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-2">
                Estudantes de <span class="text-indigo-600">{{ $professor->user->name }}</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                Listagem detalhada de todos os discentes vinculados a este orientador através de projetos ativos de TCC.
            </p>
        </div>

        <a href="{{ route('professores.consultas.index') }}" 
           class="bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center border border-gray-200 shadow-sm">
            <i class="fas fa-arrow-left mr-2 text-xs text-gray-400"></i> Voltar aos Professores
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-gray-600 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="px-6 py-4">Nome do Estudante</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Nº Matrícula</th>
                        <th class="px-6 py-4">Regime / Ano de Frequência</th>
                        <th class="px-6 py-4">Status de Acesso</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    {{-- Iterando sobre a relação mapeada no seu Model Professor: acompanhamentos() --}}
                    @forelse($professor->acompanhamentos as $acomp)
                        @php 
                            // Proteção caso o relacionamento com estudante na tabela pivot mude de nome
                            $estudante = $acomp->estudante; 
                        @endphp
                        
                        @if($estudante)
                        <tr class="transition hover:bg-slate-50/50">
                            
                            <td class="px-6 py-4 font-bold text-gray-800">
                                {{ $estudante->user->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 font-mono text-xs">
                                {{ $estudante->user->email }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 font-mono text-xs">
                                {{ $estudante->numero_matricula }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-gray-700 font-semibold text-xs">{{ $estudante->regime }}</div>
                                <div class="text-gray-400 text-[11px]">{{ $estudante->ano_frequencia }}º Ano</div>
                            </td>

                            <td class="px-6 py-4">
                                @if($estudante->user->acesso)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                        <span class="w-1 h-1 rounded-full bg-green-500"></span> Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">
                                        <span class="w-1 h-1 rounded-full bg-red-400"></span> Suspenso
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('estudantes.show', $estudante->id) }}" 
                                   class="inline-flex items-center justify-center p-2 text-gray-500 bg-slate-50 hover:text-blue-600 hover:bg-blue-50 rounded-lg border border-slate-200 transition-colors" 
                                   title="Ver Perfil do Estudante">
                                    <i class="fas fa-info-circle text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @endif
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400 italic bg-slate-50/20">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-folder-open text-3xl text-slate-200 mb-3"></i>
                                <p>Este professor não possui acompanhamentos ou orientações registradas no momento.</p>
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