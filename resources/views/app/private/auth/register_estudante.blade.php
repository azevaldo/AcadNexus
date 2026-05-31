@extends('app.private.auth.app')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-12 font-sans">

    <!-- HEADER -->
    <div class="mb-10 text-center">

        <div class="inline-flex items-center justify-center
                    w-16 h-16 rounded-2xl bg-blue-100 mb-4">

            <span class="text-3xl">🎓</span>

        </div>

        <h1 class="text-4xl font-bold text-gray-800 tracking-tight">
            Solicitar Habilitação de Estudante
        </h1>

        <p class="text-gray-500 mt-3 text-base max-w-2xl mx-auto">
            Preencha cuidadosamente os seus dados académicos para validar
            sua matrícula e obter acesso ao sistema de anteprojetos.
        </p>

    </div>


    <!-- FORM -->
    <form method="POST"
          action="{{ route('estudantes.store') }}"
          class="bg-white rounded-3xl border border-gray-200
                 shadow-xl overflow-hidden" onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">

        @csrf

        <div class="p-8 md:p-10 space-y-12">

            <!-- DADOS DE ACESSO -->
            <section>

                <div class="flex items-center gap-4 mb-8">

                    <div class="w-12 h-12 rounded-2xl bg-blue-100
                                flex items-center justify-center
                                text-blue-600 font-bold text-lg">

                        1

                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            Informações de Acesso
                        </h2>

                        <p class="text-sm text-gray-500">
                            Dados utilizados para entrar no sistema
                        </p>
                    </div>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">

                    <!-- NOME -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nome Completo
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               minlength="3"
                               maxlength="100"
                               pattern="^[A-Za-zÀ-ÿ\s]+$"
                               required
                               class="w-full px-5 py-3 rounded-2xl border border-gray-300
                                      bg-gray-50 focus:bg-white
                                      focus:ring-4 focus:ring-blue-500/10
                                      focus:border-blue-500 outline-none transition"
                               placeholder="Digite seu nome completo">

                        @error('name')
                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- EMAIL -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Institucional
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="w-full px-5 py-3 rounded-2xl border border-gray-300
                                      bg-gray-50 focus:bg-white
                                      focus:ring-4 focus:ring-blue-500/10
                                      focus:border-blue-500 outline-none transition"
                               placeholder="exemplo@universidade.com">

                        @error('email')
                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- SENHA -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Senha
                        </label>

                        <input type="password"
                               name="password"
                               minlength="6"
                               required
                               class="w-full px-5 py-3 rounded-2xl border border-gray-300
                                      bg-gray-50 focus:bg-white
                                      focus:ring-4 focus:ring-blue-500/10
                                      focus:border-blue-500 outline-none transition"
                               placeholder="Mínimo 6 caracteres">

                        @error('password')
                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- CONFIRMAR SENHA -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirmar Senha
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               minlength="6"
                               required
                               class="w-full px-5 py-3 rounded-2xl border border-gray-300
                                      bg-gray-50 focus:bg-white
                                      focus:ring-4 focus:ring-blue-500/10
                                      focus:border-blue-500 outline-none transition"
                               placeholder="Repita sua senha">

                    </div>

                </div>

            </section>


            <!-- DIVIDER -->
            <div class="border-t border-gray-100"></div>


            <!-- DADOS ACADÉMICOS -->
            <section>

                <div class="flex items-center gap-4 mb-8">

                    <div class="w-12 h-12 rounded-2xl bg-green-100
                                flex items-center justify-center
                                text-green-600 font-bold text-lg">

                        2

                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            Informações Académicas
                        </h2>

                        <p class="text-sm text-gray-500">
                            Dados relacionados ao seu curso
                        </p>
                    </div>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-7">

                    <!-- CURSO -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Curso
                        </label>

                        <select name="curso_id"
                                required
                                class="w-full px-5 py-3 rounded-2xl border border-gray-300
                                       bg-gray-50 focus:bg-white
                                       focus:ring-4 focus:ring-green-500/10
                                       focus:border-green-500 outline-none transition">

                            <option value="">
                                Selecionar curso
                            </option>

                            @foreach($cursos as $curso)

                                <option value="{{ $curso->id }}"
                                    {{ old('curso_id') == $curso->id ? 'selected' : '' }}>

                                    {{ $curso->nome }}

                                </option>

                            @endforeach

                        </select>

                        @error('curso_id')
                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- ANO -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Ano de Frequência
                        </label>

                        <select name="ano_frequencia"
                                required
                                class="w-full px-5 py-3 rounded-2xl border border-gray-300
                                       bg-gray-50 focus:bg-white
                                       focus:ring-4 focus:ring-blue-500/10
                                       focus:border-blue-500 outline-none transition">

                            <option value="">
                                Selecionar ano
                            </option>

                            <option value="4"
                                {{ old('ano_frequencia') == 4 ? 'selected' : '' }}>
                                4º Ano
                            </option>

                            <option value="5"
                                {{ old('ano_frequencia') == 5 ? 'selected' : '' }}>
                                5º Ano
                            </option>

                        </select>

                        @error('ano_frequencia')
                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- MATRÍCULA -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Número de Matrícula
                        </label>

                        <input type="text"
                               name="numero_matricula"
                               value="{{ old('numero_matricula') }}"
                               required
                               class="w-full px-5 py-3 rounded-2xl border border-gray-300
                                      bg-gray-50 focus:bg-white
                                      focus:ring-4 focus:ring-blue-500/10
                                      focus:border-blue-500 outline-none transition"
                               placeholder="Digite sua matrícula">

                        @error('numero_matricula')
                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- BI -->
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Bilhete de Identidade
                        </label>

                        <input type="text"
                               name="bi"
                               value="{{ old('bi') }}"
                               required
                               maxlength="14"
                               pattern="^\d{9}[A-Za-z]{2}\d{3}$"
                               class="w-full px-5 py-3 rounded-2xl border border-gray-300
                                      bg-gray-50 uppercase
                                      focus:bg-white
                                      focus:ring-4 focus:ring-blue-500/10
                                      focus:border-blue-500 outline-none transition"
                               placeholder="000000000LA000">

                        @error('bi')
                            <p class="text-red-500 text-sm mt-2">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- REGIME -->
                    <div class="md:col-span-2">

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Regime de Estudo
                        </label>

                        <select name="regime"
                                required
                                class="w-full px-5 py-3 rounded-2xl border border-gray-300
                                       bg-gray-50 focus:bg-white
                                       focus:ring-4 focus:ring-blue-500/10
                                       focus:border-blue-500 outline-none transition">

                            <option value="Regular"
                                {{ old('regime') == 'Regular' ? 'selected' : '' }}>
                                Regular
                            </option>

                            <option value="Pós Laboral"
                                {{ old('regime') == 'Pós Laboral' ? 'selected' : '' }}>
                                Pós Laboral
                            </option>

                        </select>

                    </div>

                </div>

            </section>

        </div>


        <!-- FOOTER -->
        <div class="px-10 py-6 bg-gray-50 border-t border-gray-100
                    flex flex-col md:flex-row justify-between items-center gap-5">

            <div class="flex gap-6 text-sm">

                <a href="{{ route('login') }}"
                   class="text-gray-500 hover:text-blue-600 transition">
                    Já possui conta?
                </a>

                <a href="{{ route('home') }}"
                   class="text-gray-500 hover:text-blue-600 transition">
                    Página Inicial
                </a>

            </div>


            <div class="flex gap-4">

                <button type="reset"
                    class="px-6 py-3 rounded-xl bg-gray-200
                           hover:bg-gray-300 font-semibold transition">

                    Limpar

                </button>

                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-blue-600
                           hover:bg-blue-700 text-white font-bold
                           shadow-lg shadow-blue-500/20 transition">

                    Concluir Registro

                </button>

            </div>

        </div>

    </form>

</div>

@endsection