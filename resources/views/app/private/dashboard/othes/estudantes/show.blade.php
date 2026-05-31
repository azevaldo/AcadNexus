@extends('app.private.dashboard.app..templete')

@section('title', 'Perfil do Estudante')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                Perfil do Estudante
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                Visualização completa dos dados cadastrais e acadêmicos.
            </p>
        </div>
        <div>
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-xl text-xs font-bold text-gray-600 bg-white hover:bg-gray-50 shadow-sm transition-all">
                <i class="fas fa-arrow-left"></i> Voltar à Listagem
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white border rounded-2xl p-6 shadow-sm text-center">
                <div class="w-20 h-20 bg-indigo-50 border border-indigo-100 rounded-full flex items-center justify-center font-black text-2xl text-indigo-600 mx-auto mb-4 shadow-inner">
                    {{ substr($estudante->user->name, 0, 1) }}
                </div>
                <h2 class="text-lg font-bold text-gray-800 leading-tight">{{ $estudante->user->name }}</h2>
                <p class="text-xs text-gray-400 font-medium mt-1">{{ $estudante->numero_matricula }}</p>

                <div class="mt-6 pt-6 border-t flex justify-around text-center">
                    <div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Ano Frequência</p>
                        <p class="text-lg font-bold text-gray-800 mt-1">{{ $estudante->ano_frequencia }}º Ano</p>
                    </div>
                    <div class="border-l h-10 my-auto"></div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-wider">Regime</p>
                        <p class="text-sm font-bold text-gray-800 mt-1.5">{{ $estudante->regime }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Conta no Sistema</h4>
                    <p class="text-[11px] text-gray-400 mt-0.5">Status de login do usuário</p>
                </div>
                <div>
                    @if($estudante->user->acesso)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Ativo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Suspenso
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            
            <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Dados do Estudante</h3>
                </div>
                
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Nome Completo</label>
                        <p class="text-sm font-bold text-gray-700 mt-1 bg-gray-50 border rounded-xl px-4 py-2.5">{{ $estudante->user->name }}</p>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">E-mail Institucional</label>
                        <p class="text-sm font-bold text-gray-700 mt-1 bg-gray-50 border rounded-xl px-4 py-2.5 break-all">{{ $estudante->user->email }}</p>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Número de BI</label>
                        <p class="text-sm font-bold text-gray-700 mt-1 bg-gray-50 border rounded-xl px-4 py-2.5">{{ $estudante->bi }}</p>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Curso Matriculado</label>
                        <p class="text-sm font-bold text-indigo-600 mt-1 bg-indigo-50/30 border border-indigo-100 rounded-xl px-4 py-2.5">
                            {{ $estudante->curso->nome ?? 'Curso não identificado' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Corpo Técnico de Orientação</h3>
                    <span class="text-[11px] font-bold text-gray-400">Relação Ativa</span>
                </div>

                <div class="p-6">
                    @if($estudante->tutorRelacao && $estudante->tutorRelacao->ativo)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <div class="p-4 bg-blue-50/40 border border-blue-100 rounded-xl flex items-start gap-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                    TP
                                </div>
                                <div>
                                    <p class="text-[9px] uppercase font-black text-blue-500 tracking-widest">Tutor Principal</p>
                                    <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $estudante->tutorRelacao->tutor->user->name }}</p>
                                </div>
                            </div>

                            @if($estudante->tutorRelacao->coTutor)
                                <div class="p-4 bg-gray-50/60 border border-gray-100 rounded-xl flex items-start gap-3">
                                    <div class="w-8 h-8 bg-gray-400 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                        CT
                                    </div>
                                    <div>
                                        <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest">Co-Tutor Coorientador</p>
                                        <p class="text-sm font-bold text-gray-700 mt-0.5">{{ $estudante->tutorRelacao->coTutor->user->name }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="p-4 border border-dashed rounded-xl flex items-center justify-center bg-gray-50/30">
                                    <p class="text-xs text-gray-400 italic">Sem Co-Tutor atribuído</p>
                                </div>
                            @endif

                        </div>
                    @else
                        <div class="text-center py-6 border border-dashed border-gray-200 rounded-xl">
                            <p class="text-gray-400 text-sm italic">Nenhum orientador principal ou co-tutor foi vinculado a este estudante até o momento.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection