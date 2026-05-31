@extends('app.private.dashboard.app.templete')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
     <div class="flex items-start gap-4 mb-8">
    {{-- Botão Voltar para Lista de Solicitações --}}
    <a href="{{ route('solicitacoes.estudante.index') }}" 
       class="mt-1 flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm shrink-0"
       title="Voltar para Solicitações">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div>
        <h1 class="text-3xl font-black text-gray-900 uppercase leading-none">
            Solicitar Orientação
        </h1>
        <p class="text-gray-500 font-bold uppercase text-xs tracking-widest mt-2">
            Escolha um professor disponível para enviar seu pedido
        </p>
    </div>
</div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tutores as $tutor)
            @php
                // Busca os valores corretos vindos do controller (com o fallback caso não existam)
                $limiteMaximo = $tutor->max_orientacoes ?? 3;
                $vagasOcupadas = $tutor->orientacoes_ativas_count ?? 0;
                $atingiuLimite = $vagasOcupadas >= $limiteMaximo;
                
                // Calcula a porcentagem real da barra de progresso
                $porcentagem = min(($vagasOcupadas / $limiteMaximo) * 100, 100);
            @endphp

            {{-- Card do Professor com estado reativo via Alpine --}}
            <div x-data="{ tipo: 'tutor', limiteAtingido: {{ $atingiuLimite ? 'true' : 'false' }} }" 
                 class="bg-white border-2 border-gray-900 p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex flex-col justify-between">
                
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-blue-100 border-2 border-gray-900 flex items-center justify-center font-black text-blue-600">
                            {{ substr($tutor->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 uppercase leading-tight">{{ $tutor->user->name }}</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                                {{ $tutor->area_especializacao ?? 'Área não definida' }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- Barra de Progresso e Indicador de Vagas como Tutor Principal --}}
                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between text-[10px] font-black uppercase">
                            <span class="text-gray-400">Vagas (Tutor Principal):</span>
                            <span class="{{ $atingiuLimite ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $vagasOcupadas }} / {{ $limiteMaximo }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-100 h-2 border border-gray-900">
                            <div class="{{ $atingiuLimite ? 'bg-red-500' : 'bg-blue-500' }} h-full transition-all duration-300" 
                                 style="width: {{ $porcentagem }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Formulário de Envio --}}
                <form action="{{ route('solicitacoes.store', $tutor->id) }}" method="POST"  
                onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                    @csrf
                    <div class="flex flex-col gap-3">
                        
                        {{-- Select que monitora a mudança de opção --}}
                        <select name="tipo" 
                                x-model="tipo"
                                @change="limiteAtingido = (tipo === 'tutor' && {{ $atingiuLimite ? 'true' : 'false' }})"
                                class="w-full bg-gray-50 border-2 border-gray-900 p-2 text-[10px] font-black uppercase outline-none focus:bg-white">
                            <option value="tutor">Solicitar como Tutor Principal</option>
                            <option value="co_tutor">Solicitar como Co-Tutor</option>
                        </select>
                        
                        {{-- Botão Reativo: Altera visualmente e bloqueia o clique se o limite de tutor for atingido --}}
                        <button type="submit" 
                                :disabled="limiteAtingido"
                                :class="limiteAtingido 
                                    ? 'bg-gray-200 text-gray-400 border-2 border-gray-300 cursor-not-allowed shadow-none translate-none' 
                                    : 'bg-gray-900 text-white hover:bg-blue-600 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.3)] active:shadow-none active:translate-y-1'"
                                class="w-full py-3 text-xs font-black uppercase transition-all">
                            <span x-text="limiteAtingido ? 'Limite Atingido como Tutor' : 'Enviar Solicitação'"></span>
                        </button>
                        
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection