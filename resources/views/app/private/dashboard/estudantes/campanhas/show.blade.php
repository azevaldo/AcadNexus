@extends('app.private.dashboard.app.templete')

@section('title', 'Detalhes da Campanha')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-10 lg:px-10">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ $campanha->titulo }}
            </h1>
            <p class="text-slate-500 mt-2 flex items-center">
                <span class="w-8 h-px bg-slate-300 mr-3"></span>
                Painel Geral de Informações
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('estudante.anteprojetos.meuAnteprojeto', $campanha->id) }}"
               class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-6 py-3 rounded-xl font-bold transition shadow-sm border border-indigo-100">
                <i class="fas fa-users mr-2"></i> Ver Anteprojetos
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
            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl shadow-slate-200">
                <h2 class="text-xs uppercase tracking-[0.2em] font-black text-slate-400 mb-8">Cronograma de Prazos</h2>
                
                <div class="space-y-8">
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition">
                                <i class="fas fa-upload"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase">Submissão</span>
                                <p class="font-bold text-slate-100">{{ $campanha->prazo_submissao ?? 'Não definido' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-amber-400 group-hover:scale-110 transition">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase">Melhorias</span>
                                <p class="font-bold text-slate-100">{{ $campanha->prazo_melhorias ?? 'Não definido' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase">Novo Prazo</span>
                                <p class="font-bold text-emerald-400">{{ $campanha->prazo_novo ?? 'Não definido' }}</p>
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

@endsection