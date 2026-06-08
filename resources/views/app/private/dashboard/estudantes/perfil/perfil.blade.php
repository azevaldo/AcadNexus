@extends('app.private.dashboard.app.templete')

@section('title', 'Configurações do Perfil')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 font-sans">
    
    {{-- Cabeçalho da View --}}
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Configurações do Perfil</h1>
        <p class="text-sm text-gray-500 mt-1">Gerencie os seus dados de identificação, informações de frequência e credenciais de acesso.</p>
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
        
        {{-- CARD INSTITUCIONAL: CURSO, DEPARTAMENTO E CORPO DOCENTE (APENAS LEITURA) --}}
        <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200/80 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-100/50 flex items-center justify-between">
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Vínculo Académico Institucional</h3>
                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded-md uppercase tracking-wider">Dados Protegidos</span>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Curso --}}
                    <div class="p-4 bg-white border border-gray-100 rounded-2xl shadow-2xs">
                        <span class="text-[10px] font-black text-gray-400 uppercase block mb-1">Curso</span>
                        <p class="text-sm font-bold text-gray-700">{{ $estudante->curso->nome ?? 'Não Vinculado' }}</p>
                    </div>

                    {{-- Departamento --}}
                    <div class="p-4 bg-white border border-gray-100 rounded-2xl shadow-2xs">
                        <span class="text-[10px] font-black text-gray-400 uppercase block mb-1">Departamento</span>
                        <p class="text-sm font-bold text-gray-700">{{ $estudante->curso->departamento->nome ?? 'Não Vinculado' }}</p>
                    </div>

                    {{-- Bloco de Tutoria (Tutor e Co-Tutor) --}}
                    <div class="p-5 bg-white border border-gray-100 rounded-2xl shadow-2xs md:col-span-2 space-y-4">
                        <span class="text-[10px] font-black text-gray-400 uppercase block mb-2">Orientação de TCC</span>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-50 pt-3">
                            {{-- Tutor Principal --}}
                            <div>
                                <span class="text-[10px] font-bold text-indigo-500 uppercase block mb-0.5">Tutor Principal</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ ($estudante->tutorRelacao && $estudante->tutorRelacao->tutor) ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>
                                    <p class="text-sm font-bold text-gray-700">
                                        {{ $estudante->tutorRelacao->tutor->user->name ?? ($estudante->tutorRelacao->tutor->name ?? 'Nenhum tutor atribuído') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Co-Tutor --}}
                            <div>
                                <span class="text-[10px] font-bold text-purple-500 uppercase block mb-0.5">Co-Tutor / Coorientador</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ ($estudante->tutorRelacao && $estudante->tutorRelacao->coTutor) ? 'bg-purple-500' : 'bg-gray-300' }}"></div>
                                    <p class="text-sm font-bold text-gray-700">
                                        {{ $estudante->tutorRelacao->coTutor->user->name ?? ($estudante->tutorRelacao->coTutor->name ?? 'Nenhum co-tutor atribuído') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- CARD 1: DADOS PESSOAIS E ACADÉMICOS (FORMULÁRIO EDITÁVEL) --}}
        <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Informações Pessoais e Frequência</h3>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('profile.estudante.update') }}" 
                      onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        
                        {{-- Nome Completo (Tabela: users) --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Nome Completo</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$"
                                   title="O nome deve iniciar com letra ou número, contendo pelo menos 3 caracteres."
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-medium text-sm transition-all">
                            @error('name') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- E-mail (Tabela: users) --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Endereço de E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-medium text-sm transition-all">
                            @error('email') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Bilhete de Identidade (Tabela: estudantes) --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Nº do Bilhete de Identidade (BI)</label>
                            <input type="text" name="bi" value="{{ old('bi', $estudante->bi) }}" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-medium text-sm transition-all">
                            @error('bi') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
 <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Telefone</label>
                            <input type="text" name="telefone" value="{{ old('telefone', $user->telefone) }}" required 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-bold text-sm transition-all">
                            @error('telefone') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                        {{-- Ano de Frequência (Tabela: estudantes) --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Ano de Frequência</label>
                            <input type="number" name="ano_frequencia" value="{{ old('ano_frequencia', $estudante->ano_frequencia) }}" required min="4" max="6"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-bold text-sm transition-all">
                            @error('ano_frequencia') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Regime de Estudos (Tabela: estudantes) --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Regime</label>
                            <select name="regime" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-semibold text-sm transition-all">
                                <option value="Regular" {{ old('regime', $estudante->regime) == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="Pós Laboral" {{ old('regime', $estudante->regime) == 'Pós Laboral' ? 'selected' : '' }}>Pós Laboral</option>
                            </select>
                            @error('regime') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
<input type="text" name="curso_id" value="{{$estudante->curso->id}}" hidden>
                        {{-- Nº de Matrícula (Bloqueado para alteração - Segurança) --}}
                        {{-- Nº de Matrícula (Bloqueado para alteração - Segurança) --}}
<div>
    <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Nº de Matrícula (Protegido)</label>
    <input type="text" 
           name="numero_matricula" 
           value="{{ $estudante->numero_matricula }}" 
           readonly
           class="w-full bg-gray-100 border border-gray-200 text-gray-400 font-bold rounded-xl px-4 py-3 outline-none text-sm cursor-not-allowed">
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
                <form method="POST" action="{{ route('profile.estudante.password.update') }}"
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