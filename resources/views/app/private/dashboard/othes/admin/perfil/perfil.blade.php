@extends('app.private.dashboard.app.templete')

@section('title', 'Perfil do Administrador')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 font-sans">
    
    {{-- Cabeçalho da View --}}
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Painel de Perfil Administrativo</h1>
        <p class="text-sm text-gray-500 mt-1">Gerencie suas credenciais de super-utilizador e dados de autenticação master do sistema.</p>
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
        
        {{-- CARD 1: DADOS DA CONTA DE ADMINISTRADOR --}}
        <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Identificação do Administrador</h3>
                <span class="px-2.5 py-1 bg-rose-50 text-rose-700 text-[10px] font-bold rounded-md uppercase tracking-wider">Acesso Total</span>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('profile.admin.update') }}" 
                      onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A salvar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        
                        {{-- Nome do Admin --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Nome de Administrador</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$"
                                   title="O nome deve iniciar com letra ou número, contendo pelo menos 3 caracteres."
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-medium text-sm transition-all">
                            @error('name') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- E-mail do Admin --}}
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">E-mail Principal</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-medium text-sm transition-all">
                            @error('email') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
 <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Telefone</label>
                            <input type="text" name="telefone" value="{{ old('telefone', $user->telefone) }}" required 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:bg-white outline-none font-bold text-sm transition-all">
                            @error('telefone') <p class="text-rose-600 text-xs mt-1 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-6">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs rounded-xl transition-all uppercase tracking-widest shadow-md hover:shadow-lg">
                            Atualizar Dados Gerais
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CARD 2: SEGURANÇA AVANÇADA (ALTERAÇÃO DE SENHA ADM) --}}
        <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Segurança e Credenciais Master</h3>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('profile.admin.password.update') }}"
                      onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A alterar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
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
                            <label class="text-[10px] font-black text-gray-400 uppercase mb-2 block">Nova Senha Master</label>
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
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gray-900 hover:bg-rose-700 text-white font-black text-xs rounded-xl transition-all uppercase tracking-widest shadow-md hover:shadow-lg">
                            Modificar Senha Master
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection