@extends('app.private.dashboard.app..templete')

@section('title','Adicionar Estudante')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            Adicionar Estudante
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Registre um novo estudante no sistema
        </p>
    </div>


    <form method="POST"
          action="{{ route('estudantes.store') }}"
          class="bg-white border border-gray-200 rounded-xl">

        @csrf

        <div class="p-6 space-y-8">

            <!-- USUÁRIO -->
            <div>

                <h2 class="text-sm font-semibold text-gray-700 mb-4">
                    Informações do Usuário
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="text-sm text-gray-600">Nome</label>
                        <input type="text"
                               name="name"
                               class="w-full mt-1 px-3 py-2 border rounded-lg
                                      focus:ring-2 focus:ring-blue-500
                                      focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <input type="email"
                               name="email"
                               class="w-full mt-1 px-3 py-2 border rounded-lg
                                      focus:ring-2 focus:ring-blue-500
                                      focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Senha</label>
                        <input type="password"
                               name="password"
                               class="w-full mt-1 px-3 py-2 border rounded-lg
                                      focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">
                            Confirmar Senha
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="w-full mt-1 px-3 py-2 border rounded-lg
                                      focus:ring-2 focus:ring-blue-500">
                    </div>

                </div>

            </div>


            <!-- DIVIDER -->
            <div class="border-t"></div>


            <!-- ACADÊMICO -->
            <div>

                <h2 class="text-sm font-semibold text-gray-700 mb-4">
                    Informações Acadêmicas
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="text-sm text-gray-600">
                            Curso
                        </label>

                        <select name="curso_id"
                                class="w-full mt-1 px-3 py-2 border rounded-lg
                                       focus:ring-2 focus:ring-blue-500"
                                required>

                            <option value="">Selecionar curso</option>

                            @foreach($cursos as $curso)

                                <option value="{{ $curso->id }}">
                                    {{ $curso->nome }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">
                            Número de Matrícula
                        </label>

                        <input type="text"
                               name="numero_matricula"
                               class="w-full mt-1 px-3 py-2 border rounded-lg
                                      focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">
                            Regime
                        </label>

                        <select name="regime"
                                class="w-full mt-1 px-3 py-2 border rounded-lg
                                       focus:ring-2 focus:ring-blue-500">

                            <option value="Regular">Regular</option>
                            <option value="Pós Laboral">Pós Laboral</option>

                        </select>
                    </div>

                </div>

            </div>

        </div>


        <!-- FOOTER -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">

            <a href="{{ route('estudantes.index') }}"
               class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
                Cancelar
            </a>

            <button type="submit"
                class="px-5 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">

                Salvar Estudante

            </button>

        </div>

    </form>

</div>

@endsection