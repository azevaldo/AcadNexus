@extends('app.private.dashboard.app.templete')

@section('title', 'Meus Anteprojetos')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-10">
    
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="text-blue-600 font-bold text-xs uppercase tracking-widest">Campanha Ativa</span>
            <h1 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $campanha->titulo }}</h1>
            <p class="text-slate-500 text-sm mt-2">
                <i class="far fa-calendar-alt mr-1"></i> Prazo de Submissão: 
                <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($campanha->prazo_submissao)->format('d/m/Y') }}</span>
            </p>
            <p class="text-slate-500 text-sm mt-2">
                <i class="far fa-calendar-alt mr-1"></i> Prazo de Melhorias: 
                <span class="font-semibold text-slate-700">{{($campanha->prazo_melhorias)? \Carbon\Carbon::parse($campanha->prazo_melhorias)->format('d/m/Y'):'Não Definido' }}</span>
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if($anteprojetos->isEmpty() )
                @if($fase1==true)    
                    <a href="{{ route('estudante.anteprojetos.create', $campanha->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center">
                        <i class="fas fa-plus mr-2"></i> Iniciar Anteprojeto
                    </a>
                @else
            
                    <div class="alert text-danger">Prazo de Submissão Esgotado</div>
                
                @endif
            @else 
                @php $ultimo = $anteprojetos->first(); @endphp
                {{-- Só mostra o botão de nova versão se o coordenador pediu melhorias --}}
               
               @if($ultimo->status=='aprovado')
                    <div class="alert">Aprovado Com Sucesso</div>
                @else
                @if($campanha->prazo_melhorias)
                    @if($fase2==true)
                        @if($ultimo->status == 'melhorias')
                            <a href="{{ route('estudante.anteprojetos.nova_versao', $ultimo->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-amber-200 transition-all flex items-center">
                                <i class="fas fa-sync-alt mr-2"></i> Enviar Nova Versão
                            </a>
                        @elseif($ultimo->status =='rejeitado' && $totalAnteprojetos==1  && $totalVersoes==1)
                            <a href="{{ route('estudante.anteprojetos.create', $campanha->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-amber-200 transition-all flex items-center">
                                <i class="fas fa-sync-alt mr-2"></i>    Novo Anteprojeto
                            </a>
                        @elseif($ultimo->status =='rejeitado' && $totalAnteprojetos==1  && $totalVersoes>1)
                        <div class="alert">Espere uma outra campanha...</div>
                        @elseif($ultimo->status =='rejeitado' && $totalAnteprojetos>1)
                        <div class="alert">Espere uma outra campanha...</div>
                        @endif
                    @else
                    <div class="alert">Prazo Para Melhorias Esgotado</div>
                    @endif
                @endif

            @endif

        @endif
        </div>
    </div>

    @if($anteprojetos->isEmpty())
        <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-20 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-folder-open text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Nenhum anteprojeto iniciado</h3>
            <p class="text-slate-500 mt-2 max-w-sm mx-auto">Você ainda não submeteu nenhuma proposta para esta campanha acadêmica.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest ml-2">Todos Anteprojetos</h2>

                @foreach($anteprojetos as $projeto)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-5">
                                <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-blue-600 border border-slate-100 shrink-0">
                                    <i class="fas fa-file-alt text-xl"></i>
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <h3 class="text-lg font-bold text-slate-900 leading-tight">
                                            {{ $projeto->titulo ?? 'Anteprojeto sem título' }}
                                        </h3>
                                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold uppercase">
                                            V{{ $projeto->numero_versao }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-slate-500 text-sm line-clamp-2 mb-4">
                                        {{ $projeto->descricao }}
                                    </p>

                                    <div class="flex flex-wrap items-center gap-4">
                                        @switch($projeto->status)
                                            @case('aprovado')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                                    <i class="fas fa-check-circle mr-1.5"></i> Aprovado
                                                </span>
                                                @break
                                            @case('rejeitado')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                                    <i class="fas fa-times-circle mr-1.5"></i> Rejeitado
                                                </span>
                                                @break
                                            @case('melhorias')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                                    <i class="fas fa-exclamation-triangle mr-1.5"></i> Requer Melhorias
                                                </span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                                    <i class="fas fa-clock mr-1.5"></i> Em Análise
                                                </span>
                                        @endswitch

                                        <span class="text-slate-400 text-xs">
                                            <i class="far fa-calendar mr-1"></i> {{ $projeto->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('estudante.anteprojetos.show', $projeto->id) }}" 
                               class="bg-slate-50 hover:bg-blue-600 hover:text-white text-slate-500 p-4 rounded-2xl transition-all border border-slate-100" 
                               title="Visualizar">
                                <i class="fas fa-eye text-lg"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="space-y-6">
                <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl">
                    <h4 class="text-lg font-bold mb-4 flex items-center text-blue-400">
                        <i class="fas fa-info-circle mr-2"></i> Próximos Passos
                    </h4>
                    <div class="space-y-4 text-xs leading-relaxed">
                        <div class="flex gap-3">
                            <span class="text-blue-400 font-bold">1.</span>
                            <p class="text-slate-400">O Coordenador revisará seu documento.</p>
                        </div>
                        <div class="flex gap-3">
                            <span class="text-blue-400 font-bold">2.</span>
                            <p class="text-slate-400">Fique atento ao seu status: se mudar para "Melhorias", você deve corrigir e reenviar.</p>
                        </div>
                        <div class="flex gap-3">
                            <span class="text-blue-400 font-bold">3.</span>
                            <p class="text-slate-400">Se mudar para "Rejeitado", você deve enviar um outro anteprojeto.</p>
                        </div>
                        <div class="flex gap-3">
                            <span class="text-blue-400 font-bold">4.</span>
                            <p class="text-slate-400">Após a aprovação final, você poderá prosseguir para a fase de tutoria.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50 rounded-3xl p-6 border border-indigo-100">
                    <p class="text-indigo-900 font-bold text-sm">Atenção</p>
                    <p class="text-indigo-600 text-xs mt-2">
                        Submissões fora do prazo não serão aceitas pelo sistema. Garanta que seu arquivo PDF está legível.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection