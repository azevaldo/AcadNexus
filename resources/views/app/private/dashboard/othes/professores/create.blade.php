 @extends('app.private.dashboard.app..templete')
@section('title','Adicionar Professor')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Título -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            Adicionar Professor
        </h1>
        <p class="text-gray-500 mt-1">
            Preencha os dados do professor e associe aos departamentos e áreas científicas.
        </p>
    </div>

    <!-- Erros -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Card -->
    <form method="POST" action="{{ route('professores.store') }}"
          class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 space-y-8">
        @csrf

        <!-- Dados principais -->
        <div>
            <h2 class="text-lg font-semibold text-gray-700 mb-4">
                Dados do Professor
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nome
                    </label>
                    <input type="text"
       name="name"
      
       required
       minlength="3"
       maxlength="100"
       pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$"
       title="O nome deve possuir entre 3 e 100 caracteres e conter apenas letras, números e espaços."
       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-nonee">

<p class="text-xs text-gray-500 mt-2">
    Mínimo de 3 caracteres. Apenas letras, números e espaços.
</p>
                     
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input type="email" name="email"
                           value="{{ old('email') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="email@exemplo.com"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Senha
                    </label>
                    <input type="password" name="password"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Confirmar Senha
                    </label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           required>
                </div>
            </div>
        </div>

        <!-- Associações -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Departamentos -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Departamentos
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($departamentos as $dep)
                        <label class="flex items-center gap-3 bg-gray-50 border rounded-xl px-4 py-2 hover:bg-blue-50 cursor-pointer">
                            <input type="checkbox"
                                   name="departamentos[]"
                                   value="{{ $dep->id }}"
                                   class="rounded text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700">{{ $dep->nome }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Áreas Científicas -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Áreas Científicas
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($areas as $area)
                        <label class="flex items-center gap-3 bg-gray-50 border rounded-xl px-4 py-2 hover:bg-blue-50 cursor-pointer">
                            <input type="checkbox"
                                   name="areas[]"
                                   value="{{ $area->id }}"
                                   class="rounded text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700">{{ $area->nome }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Botões -->
        <div class="flex flex-col sm:flex-row justify-end gap-4 pt-4 border-t">
            <a href="{{ route('professores.index') }}"
               class="px-6 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 text-center">
                Cancelar
            </a>

            <button type="submit"
                    class="px-6 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 shadow">
                Salvar Professor
            </button>
        </div>

    </form>

</div>
@endsection