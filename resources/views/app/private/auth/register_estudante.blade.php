@extends('app.private.auth.app')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-12 font-sans">

    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
            Solicitar Habilitação de Estudante
        </h1>
        <p class="text-gray-500 mt-2 text-base">
            Preencha seus dados para validar sua matrícula e iniciar seu processo de anteprojeto.
        </p>
    </div>

    <form method="POST"
          action="{{ route('estudantes.store') }}"
          class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        @csrf

        <div class="p-8 md:p-10 space-y-10">

            <section>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center font-bold text-sm">
                        1
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Informações de Acesso</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700 ml-1">Nome Completo</label>
                        <input type="text" name="name"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                               placeholder="Seu nome acadêmico" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700 ml-1">Email Institucional</label>
                        <input type="email" name="email"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                               placeholder="exemplo@escola.com" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700 ml-1">Senha</label>
                        <input type="password" name="password"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                               required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700 ml-1">Confirmar Senha</label>
                        <input type="password" name="password_confirmation"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                               required>
                    </div>
                </div>
            </section>

            <hr class="border-gray-100">

            <section>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 bg-green-50 text-green-600 rounded-full flex items-center justify-center font-bold text-sm">
                        2
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Dados Acadêmicos</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-sm font-medium text-gray-700 ml-1">Curso</label>
                        <select name="curso_id"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-600 transition-all"
                                required>
                            <option value="">Selecionar seu curso</option>
                            @foreach($cursos as $curso)
                                <option value="{{ $curso->id }}">{{ $curso->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700 ml-1">Ano de Frequência</label>
                        <input type="number" name="ano_frequencia" min="1" max="6"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                               placeholder="Ex: 4" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700 ml-1">Nº de Matrícula</label>
                        <input type="text" name="numero_matricula"
                               class="w-full px-4 py-2.5 bg-white border border-blue-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold text-blue-600 uppercase"
                               placeholder="Matrícula" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700 ml-1">BI (Nº Identidade)</label>
                        <input type="text" name="bi"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all uppercase"
                               placeholder="Nº do BI" required>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700 ml-1">Regime de Estudo</label>
                        <select name="regime"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="Regular">Regular</option>
                            <option value="Pós Laboral">Pós Laboral</option>
                        </select>
                    </div>
                </div>
            </section>
        </div>

        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex gap-6">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                    Já possui conta? Entrar
                </a>
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors">
                    Página Inicial
                </a>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('estudantes.index') }}"
                   class="px-6 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition-all">
                    Cancelar
                </a>

                <button type="submit"
                    class="px-8 py-2.5 text-sm font-bold bg-blue-600 text-white rounded-xl shadow-md shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition-all">
                    Concluir Registro
                </button>
            </div>
        </div>
    </form>

    <div class="mt-8 text-center">
        <p class="text-xs text-gray-400">
            Dificuldades no cadastro? Entre em contato com a Secretaria Acadêmica.
        </p>
    </div>

</div>

@endsection