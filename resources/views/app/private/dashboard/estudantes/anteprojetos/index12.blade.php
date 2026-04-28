@extends('app.private.dashboard.app.templete')

@section('title', 'Meus Anteprojetos')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-10">
    
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="text-blue-600 font-bold text-xs uppercase tracking-widest">Campanha Atual2</span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $campanha->titulo }}</h1>
            <p class="text-slate-500 text-sm mt-2">
                <i class="far fa-calendar-alt mr-1"></i> Prazo final: 
                <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($campanha->prazo_submissao)->format('d/m/Y') }}</span>
            </p>
        </div>

        <a href="{{route('estudante.anteprojetos.create', $campanha->id)}}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center justify-center">
            <i class="fas fa-cloud-upload-alt mr-2"></i> Submeter Anteprojeto
        </a>
        <a href="{{route('estudante.anteprojetos.nova_versao',$anteprojetos->last()->id)}}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center justify-center">
            <i class="fas fa-cloud-upload-alt mr-2"></i> Nova versão
        </a>
    </div>

    @if($anteprojetos->isEmpty())
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-20 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-file-upload text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Nenhum arquivo enviado</h3>
            <p class="text-slate-500 mt-2 max-w-sm mx-auto">Ainda não submeteste o teu anteprojeto para esta campanha. Clica no botão acima para começar.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2">
                <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4">Versão Atual</h2>
                @php $atual = $anteprojetos->first(); @endphp
                
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8">
                        @switch($atual->status)
                            @case('aprovado')
                                <span class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-tight flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i> Aprovado
                                </span>
                                @break
                            @case('rejeitado')
                                <span class="bg-red-100 text-red-700 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-tight flex items-center">
                                    <i class="fas fa-times-circle mr-2"></i> Rejeitado
                                </span>
                                @break
                            @case('melhorias')
                                <span class="bg-amber-100 text-amber-700 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-tight flex items-center">
                                    <i class="fas fa-wrench mr-2"></i> Requer Melhorias
                                </span>
                                @break
                            @default
                                <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-tight flex items-center border border-blue-200">
                                    <i class="fas fa-clock mr-2 animate-spin-slow"></i> Submetido
                                </span>
                        @endswitch
                    </div>

                    <div class="flex items-start gap-6">
                        <div class="w-16 h-20 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 border border-slate-100 shrink-0">
                            <i class="fas fa-file-pdf text-3xl"></i>
                        </div>
                        <div class="pr-24">
                            <h3 class="text-xl font-bold text-slate-900 leading-tight">Versão {{ $atual->versao }}</h3>
                            <p class="text-slate-500 text-sm mt-1 line-clamp-2">{{ $atual->descricao }}</p>
                            
                            <div class="mt-6 flex items-center gap-4">
                                
                                <a href="{{ route('estudante.anteprojetos.show',$atual->id) }}" target="_self" class="text-blue-600 font-bold text-sm hover:underline">
                                    <i class="fas fa-download mr-1"></i> Vizualizar
                                </a>
                                <a href="{{ asset('storage/'.$atual->arquivo) }}" target="_blank" class="text-blue-600 font-bold text-sm hover:underline">
                                    <i class="fas fa-download mr-1"></i> Descarregar Arquivo
                                </a>
                                <span class="text-slate-300">|</span>
                                <span class="text-slate-400 text-xs italic">Enviado em {{ $atual->created_at->format('d/m/Y \à\s H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 text-center">Histórico de Versões</h2>
                    <div class="space-y-4">
                        @foreach($anteprojetos as $projeto)
                            <div class="bg-white/60 hover:bg-white p-5 rounded-2xl border border-slate-100 transition-all flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    <div class="text-slate-400 font-bold text-lg w-8">{{ $projeto->versao }}</div>
                                    <div class="h-8 w-px bg-slate-200"></div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700">Submissão de {{ $projeto->created_at->format('d M, Y') }}</p>
                                        <p class="text-xs text-slate-400">{{ Str::limit($projeto->versao, 50) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-[10px] font-bold uppercase text-slate-400">{{ $projeto->versao }}</span>
                                    <a href="#" class="p-2 text-slate-300 group-hover:text-blue-600 transition">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl">
                    <h4 class="text-lg font-bold mb-4 flex items-center">
                        <i class="fas fa-lightbulb text-amber-400 mr-2"></i> Guia Rápido
                    </h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 bg-slate-800 rounded flex items-center justify-center text-[10px] text-white shrink-0 mt-0.5">1</span>
                            Garante que o arquivo esteja em formato PDF.
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 bg-slate-800 rounded flex items-center justify-center text-[10px] text-white shrink-0 mt-0.5">2</span>
                            A descrição deve resumir as alterações feitas nesta versão.
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 bg-slate-800 rounded flex items-center justify-center text-[10px] text-white shrink-0 mt-0.5">3</span>
                            Fica atento ao feedback do teu orientador/coordenador.
                        </li>
                    </ul>
                </div>

                <div class="bg-indigo-50 rounded-3xl p-6 border border-indigo-100">
                    <p class="text-indigo-900 font-bold text-sm">Precisas de ajuda?</p>
                    <p class="text-indigo-600 text-xs mt-1 mb-4">Se tiveres problemas técnicos no upload, contacta o suporte.</p>
                    <a href="mailto:suporte@instituto.com" class="text-indigo-700 font-black text-xs uppercase tracking-tighter hover:underline">
                        Contactar Suporte →
                    </a>
                </div>
            </div>

        </div>
    @endif
</div>

<style>
    .animate-spin-slow {
        animation: spin 3s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>

@endsection