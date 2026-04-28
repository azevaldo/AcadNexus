@extends('app.private.dashboard.app.templete')

@section('title', 'Avaliar Anteprojeto')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-10">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ url()->previous() }}" class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 hover:text-blue-600 transition shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-100">
                {{ substr($anteprojeto->estudante->user->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $anteprojeto->estudante->user->name }}</h2>
                <p class="text-slate-400 text-xs tracking-wide uppercase font-bold">{{ $anteprojeto->estudante->user->email }}</p>
            </div>
        </div>
        <div class="px-4 py-2 bg-slate-50 rounded-2xl border border-slate-100">
            <span class="text-[10px] text-slate-400 font-black uppercase block tracking-widest">Campanha</span>
            <span class="text-sm font-bold text-slate-700">{{ $anteprojeto->campanha->titulo }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <h1 class="text-2xl font-extrabold text-slate-900 mb-6 italic">"{{ $anteprojeto->titulo ?? 'Sem título' }}"</h1>
                <label class="text-[10px] uppercase font-black text-slate-400 tracking-widest block mb-3">Resumo da Proposta</label>
                <div class="bg-slate-50 p-6 rounded-2xl text-slate-600 leading-relaxed border border-slate-100">
                    {{ $anteprojeto->descricao }}
                </div>

                <div class="mt-8 p-6 bg-slate-900 rounded-2xl text-white flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-file-pdf text-3xl text-red-400"></i>
                        <div>
                            <p class="text-sm font-bold">{{ basename($anteprojeto->arquivo) }}</p>
                            <p class="text-[10px] text-slate-500 uppercase">Documento para Avaliação</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/'.$anteprojeto->arquivo) }}" target="_blank" 
                       class="bg-white/10 hover:bg-white/20 px-6 py-2.5 rounded-xl font-bold text-xs transition border border-white/10">
                        <i class="fas fa-eye mr-2"></i> Visualizar PDF
                    </a>
                </div>
            </div>@php
    // Coleta todas as decisões de todas as versões deste anteprojeto
    $historicoDecisoes = $anteprojeto->versoes()->with('decisao')->get()->pluck('decisao')->filter();
@endphp

@if($historicoDecisoes->count() > 0)
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <h3 class="text-sm font-black text-slate-800 uppercase mb-6 flex items-center">
            <i class="fas fa-history mr-2 text-indigo-500"></i> Histórico de Pareceres
        </h3>

        <div class="space-y-4">
            @foreach($historicoDecisoes as $decisao)
                <div class="p-4 rounded-2xl border border-slate-50 bg-slate-50/50">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs font-black uppercase {{ $decisao->decisao == 'aprovado' ? 'text-emerald-600' : ($decisao->decisao == 'rejeitado' ? 'text-red-600' : 'text-amber-600') }}">
                            {{ $decisao->decisao }}
                        </span>
                        <span class="text-[10px] text-slate-400">{{ $decisao->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <p class="text-xs text-slate-600 italic">"{{ $decisao->mensagem }}"</p>
                </div>
            @endforeach
        </div>
    </div>
@endif
        </div>

        <aside class="space-y-6">
            <div class="bg-white rounded-3xl p-8 shadow-xl border border-blue-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2 h-full bg-blue-600"></div>
                
                <h3 class="text-lg font-extrabold text-slate-900 mb-6">Avaliação Final</h3>

                <form action="{{ route('anteprojetos.decisao', $anteprojeto->id) }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label class="text-[10px] uppercase font-black text-slate-400 tracking-widest block mb-2">Definir Status</label>
                        <select name="decisao" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-blue-500/10 outline-none transition">
                            <option value="aprovado">Aprovar Projeto</option>
                            <option value="melhorias">Solicitar Melhorias</option>
                            <option value="rejeitado">Rejeitar Projeto</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-black text-slate-400 tracking-widest block mb-2">Parecer Técnico (Mensagem)</label>
                        <textarea name="mensagem" rows="5" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 outline-none transition resize-none"
                            placeholder="Escreva aqui o motivo da aprovação, rejeição ou os pontos que precisam de melhoria..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-100 transition transform hover:-translate-y-1">
                        Submeter Avaliação
                    </button>
                </form>
            </div>

            <div class="bg-slate-900 rounded-3xl p-6 text-white">
                <div class="flex justify-between text-xs mb-4">
                    <span class="text-slate-500 uppercase font-bold">Versão</span>
                    <span class="font-bold">V-{{ $anteprojeto->versao }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500 uppercase font-bold">Enviado em</span>
                    <span class="font-bold">{{ $anteprojeto->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </aside>

    </div>
</div>

@endsection