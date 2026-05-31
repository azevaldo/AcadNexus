@extends('app.private.dashboard.app.templete')

@section('title', 'Configurações de Perfil do Docente')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 font-sans">
    
    {{-- Cabeçalho da View --}}
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Perfil do Docente</h1>
        <p class="text-sm text-gray-500 mt-1">Gerencie os seus dados cadastrais, acompanhe os seus departamentos e atualize as suas áreas científicas de orientação.</p>
    </div>

    {{-- Alertas e Mensagens de Feedback --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-bold rounded-xl flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm font-bold rounded-xl flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-8">
        
        {{-- CARD DE STATS E VÍNCULO INSTITUCIONAL (APENAS LEITURA) --}}
        <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200/80 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-100/50 flex items-center justify-between">
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Vínculo Institucional e Métricas</h3>
                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded-md uppercase tracking-wider">Dados Administrativos</span>
            </div>
            <div class="p-8">
                {{-- Linha de Departamentos --}}
                <div class="mb-6">
                    <span class="text-[10px] font-black text-gray-400 uppercase block mb-2">Departamentos Associados</span>
                    <div class="flex flex-wrap gap-2">
                        @forelse($professor->departamentos as $dept)
                            <span class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-xl shadow-2xs">
                                {{ $dept->nome }}
                            </span>
                        @empty
                            <span class="text-sm font-semibold text-gray-400 italic">Nenhum departamento vinculado administrativamente.</span>
                        @endforelse
                    </div>
                </div>

                {{-- Linha de Métricas de Orientação --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 pt-5">
                    <div class="p-4 bg-white border border-gray-100 rounded-xl flex items-center justify-between shadow-2xs">
                        <div>
                            <span class="text-[10px] font-bold text-indigo-500 uppercase block">Como Orientador Principal</span>
                            <p class="text-2xl font-black text-gray-800 mt-0.5">
                                {{ $professor->estudantes_count }} <span class="text-xs font-normal text-gray-400">de {{ $professor->max_orientacoes }}</span>
                            </p>
                        </div>
                        <span class="text-xs font-semibold text-gray-400">Limite Ativo</span>
                    </div>

                    <div class="p-4 bg-white border border-gray-100 rounded-xl flex items-center justify-between shadow-2xs">
                        <div>
                            <span class="text-[10px] font-bold text-purple-500 uppercase block">Como Co-Orientador</span>
                            <p class="text-2xl font-black text-gray-800 mt-0.5">{{ $professor->coorientados_count }}</p>
                        </div>
                        <span class="text-xs font-semibold text-gray-400">Estudantes</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD 1: FORMULÁRIO DE DADOS CADASTRAIS, LIMITE E ÁREAS CIENTÍFICAS (EDITÁVEL) --}}
        <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Informações Pessoais e Atuação</h3>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('profile.professor.update') }}" 
                      onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        {{-- Nome Completo --}}
                        <div>

    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">
        Nome Completo
    </label>

    

    <input type="text"
           name="name"
           value="{{ old('name', $user->name) }}"
           required
           pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç ]{3,100}$"
           title="Utilize apenas letras e espaços. Mínimo de 3 caracteres."
           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3
                  focus:ring-2 focus:ring-indigo-500 focus:bg-white
                  outline-none font-medium text-sm transition-all">

    @error('name')
        <p class="text-rose-600 text-xs mt-2 font-semibold">
            {{ $message }}
        </p>
    @enderror
    <p class="text-xs text-gray-500 mb-2">
        Utilize apenas letras e espaços. Mínimo de 3 caracteres.
        Exemplo: João Manuel Silva
    </p>

</div>

                        {{-- E-mail Institucional --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Endereço de E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-medium text-sm transition-all">
                            @error('email') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- NOVO: Limite Máximo de Orientações --}}
                        <div class="md:col-span-2">
                            <div class="max-w-xs">
                                <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Vagas Máximas de Orientação (TCC)</label>
                                <input type="number" name="max_orientacoes" value="{{ old('max_orientacoes', $professor->max_orientacoes) }}" required min="1" max="50"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-bold text-sm transition-all">
                                <p class="text-[11px] text-gray-400 mt-1.5">Define a quantidade máxima de alunos que você aceita orientar simultaneamente.</p>
                                @error('max_orientacoes') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Áreas Científicas de Atuação (Múltipla Escolha Moderna) --}}
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Áreas Científicas de Especialidade (Selecione as suas)</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-gray-50 p-5 rounded-2xl border border-gray-200 max-h-60 overflow-y-auto">
                                @php
                                    $areasAssociadasIds = $professor->areas->pluck('id')->toArray();
                                @endphp
                                @forelse($todasAreas as $area)
                                    <label class="flex items-start gap-3 p-2 bg-white rounded-xl border border-gray-100 hover:border-indigo-300 transition-all cursor-pointer select-none">
                                        <input type="checkbox" name="areas[]" value="{{ $area->id }}"
                                               {{ in_array($area->id, old('areas', $areasAssociadasIds)) ? 'checked' : '' }}
                                               class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300 mt-0.5">
                                        <span class="text-xs font-bold text-gray-700">{{ $area->nome }}</span>
                                    </label>
                                @empty
                                    <p class="text-xs text-gray-400 italic col-span-2 py-2">Nenhuma área científica cadastrada no sistema.</p>
                                @endforelse
                            </div>
                            @error('areas') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-6">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs rounded-xl transition-all uppercase tracking-widest shadow-md hover:shadow-lg">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CARD 2: SEGURANÇA (ALTERAÇÃO DE SENHA) --}}
        <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Segurança da Conta</h3>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('profile.professor.password.update') }}"
                      onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A atualizar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        {{-- Senha Atual --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Senha Atual</label>
                            <input type="password" name="current_password" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none text-sm transition-all">
                            @error('current_password') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nova Senha --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Nova Senha</label>
                            <input type="password" name="password" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none text-sm transition-all">
                            @error('password') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Confirmar Nova Senha --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Confirmar Nova Senha</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none text-sm transition-all">
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-6">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gray-900 hover:bg-indigo-600 text-white font-black text-xs rounded-xl transition-all uppercase tracking-widest shadow-md hover:shadow-lg">
                            Atualizar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection