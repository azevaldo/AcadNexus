@extends('app.private.dashboard.app..templete')

@section('title','Adicionar Secretaria')

@section('content')

<div class="max-w-4xl mx-auto px-6 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            Adicionar Secretaria
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Registre um novo perfil de secretaria para gestão do sistema
        </p>
    </div>

    <form method="POST" action="{{ route('secretarias.store') }}"
          class="bg-white border border-gray-200 rounded-xl">
        @csrf

        <div class="p-6 space-y-8">
            <div>
                <h2 class="text-sm font-semibold text-gray-700 mb-4">
                    Informações de Acesso
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="text-sm text-gray-600">Nome Completo</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>

                    <div>
                        </div>

                    <div>
                        <label class="text-sm text-gray-600">Senha</label>
                        <input type="password" name="password"
                               class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Confirmar Senha</label>
                        <input type="password" name="password_confirmation"
                               class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">
            <a href="{{ route('secretarias.index') }}"
               class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
                Cancelar
            </a>
            <button type="submit"
                class="px-5 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                Salvar Secretaria
            </button>
        </div>
    </form>
</div>

@endsection