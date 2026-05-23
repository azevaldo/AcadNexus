 @extends('app.private.dashboard.app.templete')

@section('title', 'Gestão de Anteprojetos')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    {{-- HEADER E ESTATÍSTICAS --}}
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i class="fas fa-folder-tree text-xl"></i>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Painel de Anteprojetos Recebidos</h1>
                </div>
                <p class="text-slate-500 font-medium flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></span>
                    Monitorando: <span class="text-slate-800 font-bold">{{ $campanha->titulo }}</span>
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full lg:w-auto">
                {{-- Card Entregas --}}
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-center">
                    <span class="text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Total Entregas</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-black text-slate-900">{{ $campanha->anteprojetos_count }}</span>
                        <span class="text-xs font-bold text-blue-500 bg-blue-50 px-1.5 py-0.5 rounded">Projetos</span>
                    </div>
                </div>

                {{-- Card Prazo --}}
                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-center">
                    <span class="text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Data Limite</span>
                    <span class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <i class="far fa-calendar-alt text-blue-500"></i>
                        {{ \Carbon\Carbon::parse($campanha->prazo_submissao)->format('d/m/Y') }}
                    </span>
                </div>

                {{-- Status do Prazo --}}
                @php
                    $diasRestantes = \Carbon\Carbon::now()->diffInDays($campanha->prazo_submissao, false);
                    $isExpired = $diasRestantes < 0;
                @endphp
                <div class="{{ $isExpired ? 'bg-red-50 border-red-100' : 'bg-emerald-50 border-emerald-100' }} p-4 rounded-2xl border flex flex-col justify-center col-span-2 md:col-span-1 text-center md:text-left">
                    <span class="text-[10px] uppercase font-black {{ $isExpired ? 'text-red-400' : 'text-emerald-500' }} tracking-widest">Status da Campanha</span>
                    <span class="text-xs font-black {{ $isExpired ? 'text-red-700' : 'text-emerald-700' }}">
                        {{ $isExpired ? 'SUBMISSÕES ENCERRADAS' : 'RECEBENDO PROJETOS' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- LISTAGEM --}}
    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[11px] font-black uppercase text-slate-400 tracking-widest">Identificação do Aluno</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase text-slate-400 tracking-widest">Destaque da Versão</th>
                        <th class="px-6 py-5 text-[11px] font-black uppercase text-slate-400 tracking-widest text-center">Progresso</th>
                        <th class="px-8 py-5 text-[11px] font-black uppercase text-slate-400 tracking-widest text-right">Ações de Gestão</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($anteprojetos as $item)
                        @php $ultimaVersao = $item->versoes->last(); @endphp
                        <tr class="hover:bg-blue-50/30 transition-all duration-300 group">
                            {{-- Coluna Aluno --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-12 h-12 bg-gradient-to-br from-slate-800 to-slate-900 text-white rounded-2xl flex items-center justify-center font-bold text-sm shadow-md transition-transform group-hover:scale-110">
                                            {{ substr($item->estudante->user->name, 0, 2) }}
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-lg shadow-sm border border-slate-100 flex items-center justify-center">
                                            <i class="fas fa-graduation-cap text-[8px] text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-slate-900 group-hover:text-blue-700 transition-colors">{{ $item->titulo }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs font-semibold text-slate-500">{{ $item->estudante->user->name }}</span>
                                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item->estudante->curso->nome }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Coluna Versão --}}
                            <td class="px-6 py-6">
                                <div class="inline-flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 group-hover:bg-white transition-colors">
                                    <div class="text-center pr-3 border-r border-slate-200">
                                        <span class="block text-[10px] font-black text-slate-400 uppercase leading-none">Versão</span>
                                        <span class="text-lg font-black text-blue-600">{{ $ultimaVersao->numero_versao ?? '1' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-700">{{ $ultimaVersao->created_at->format('d/m/Y') }}</span>
                                        <span class="text-[10px] font-medium text-slate-400 italic">Enviado às {{ $ultimaVersao->created_at->format('H:i') }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Coluna Status --}}
                            <td class="px-6 py-6">
                                <div class="flex flex-col items-center gap-1">
                                    @php
                                        $statusConfig = [
                                            'submetido' => ['color' => 'blue', 'icon' => 'fa-paper-plane'],
                                            'aprovado'  => ['color' => 'emerald', 'icon' => 'fa-check-double'],
                                            'rejeitado' => ['color' => 'red', 'icon' => 'fa-times'],
                                            'melhorias' => ['color' => 'amber', 'icon' => 'fa-pen-fancy'],
                                        ][$item->status] ?? ['color' => 'slate', 'icon' => 'fa-info-circle'];
                                        $c = $statusConfig['color'];
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border bg-{{ $c }}-50 border-{{ $c }}-100 text-{{ $c }}-600 flex items-center gap-2">
                                        <i class="fas {{ $statusConfig['icon'] }} text-[9px]"></i>
                                        {{ $item->status }}
                                    </span>
                                </div>
                            </td>

                            {{-- Coluna Ações --}}
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    {{-- Botão Visualizar --}}
                                    <a href="{{ asset('storage/'.$ultimaVersao->arquivo) }}" target="_blank" 
                                       class="group/btn relative w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white rounded-xl transition-all duration-300" 
                                       title="Visualizar PDF">
                                        <i class="fas fa-file-pdf text-sm"></i>
                                        <span class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover/btn:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">Ver Documento</span>
                                    </a>

                                     

                                    {{-- Botão Histórico --}}
                                    <a href="{{route('anteprojetosAdmin.historico',$item->id)}}" 
                                       class="w-10 h-10 flex items-center justify-center bg-slate-900 text-white hover:bg-blue-600 rounded-xl transition-all duration-300 shadow-lg shadow-slate-200" 
                                       title="Ver todo o histórico">
                                        <i class="fas fa-layer-group text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center">
                                <div class="relative inline-block mb-4">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-200">
                                        <i class="fas fa-inbox text-4xl"></i>
                                    </div>
                                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white rounded-full shadow-sm flex items-center justify-center border border-slate-100">
                                        <i class="fas fa-search text-xs text-slate-300"></i>
                                    </div>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">Nenhuma submissão encontrada</h3>
                                <p class="text-slate-400 text-sm max-w-xs mx-auto">Aguarde os estudantes enviarem seus anteprojetos para iniciar a curadoria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Rodapé --}}
        <div class="bg-slate-50/80 px-8 py-4 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
            <span>Exibindo {{ $anteprojetos->count() }} Anteprojetos Recebidos</span>
            <div class="flex gap-4">
                <span class="flex items-center gap-2"><i class="fas fa-circle text-blue-400 text-[6px]"></i> Submetido</span>
                <span class="flex items-center gap-2"><i class="fas fa-circle text-emerald-400 text-[6px]"></i> Aprovado</span>
                <span class="flex items-center gap-2"><i class="fas fa-circle text-amber-400 text-[6px]"></i> Melhorias</span>
            </div>
        </div>
    </div>
</div>

@endsection