 @extends('app.private.dashboard.app..templete')

@section('title','Editar Professor')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <div class="flex items-start gap-4">
        {{-- Botão Voltar Minimalista --}}
        <a href="{{ url()->previous()  }}"
   class="mt-1 flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm shrink-0"
   title="Voltar">
    <i class="fas fa-arrow-left"></i>
</a>

        <div>
            <h1 class="text-3xl font-bold text-gray-800 leading-tight">
                Editar Professor
            </h1>
            <p class="text-gray-500 mt-1 text-sm">
                Atualize os dados do professor e suas áreas de atuação
            </p>
        </div>
    </div>
</div>

    <form method="POST"
          action="{{ route('professores.update',$professor) }}"
          class="bg-white rounded-3xl shadow-xl border border-gray-100">

        @csrf
        @method('PUT')

        <div class="p-8 space-y-10">

            <!-- DADOS PESSOAIS -->
            <div>

                <h2 class="text-lg font-semibold text-gray-700 mb-6">
                    Informações do Professor
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Nome
                        </label>
 <input type="text"
       name="nome"
      value="{{ $professor->user->name }}"
       required
       minlength="3"
       maxlength="100"
       pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$"
       title="O nome deve possuir entre 3 e 100 caracteres e conter apenas letras, números e espaços."
       class="w-full px-4 py-3 rounded-xl border
                                      focus:ring-2 focus:ring-blue-500
                                      focus:border-blue-500">

<p class="text-xs text-gray-500 mt-2">
    Mínimo de 3 caracteres. Apenas letras, números e espaços.
</p>
                         
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ $professor->user->email }}"
                               required
                               class="w-full px-4 py-3 rounded-xl border
                                      focus:ring-2 focus:ring-blue-500
                                      focus:border-blue-500">
                    </div>

                </div>

            </div>


            <!-- DEPARTAMENTOS -->
            <div>

                <h2 class="text-lg font-semibold text-gray-700 mb-6">
                    Departamentos
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

                    @foreach($departamentos as $dep)

                    <label class="flex items-center gap-3 p-4 border rounded-xl
                                   hover:bg-blue-50 cursor-pointer
                                   transition">

                        <input type="checkbox"
                               name="departamentos[]"
                               value="{{ $dep->id }}"
                               {{ $professor->departamentos->contains($dep->id) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 rounded
                                      focus:ring-blue-500">

                        <span class="text-gray-700 font-medium">
                            {{ $dep->nome }}
                        </span>

                    </label>

                    @endforeach

                </div>

            </div>


            <!-- ÁREAS CIENTÍFICAS -->
            <div>

                <h2 class="text-lg font-semibold text-gray-700 mb-6">
                    Áreas Científicas
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">

                    @foreach($areas as $area)

                    <label class="flex items-center gap-3 p-4 border rounded-xl
                                   hover:bg-blue-50 cursor-pointer
                                   transition">

                        <input type="checkbox"
                               name="areas[]"
                               value="{{ $area->id }}"
                               {{ $professor->areas->contains($area->id) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 rounded
                                      focus:ring-blue-500">

                        <span class="text-gray-700 font-medium">
                            {{ $area->nome }}
                        </span>

                    </label>

                    @endforeach

                </div>

            </div>


            <!-- BOTÕES -->
            <div class="flex justify-end gap-4 pt-6 border-t">

                <a href="{{ route('professores.index') }}"
                   class="px-5 py-2 rounded-xl bg-gray-200
                          hover:bg-gray-300 transition">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-6 py-2 rounded-xl
                               bg-green-600 text-white font-semibold
                               shadow hover:bg-green-700 transition">

                    Atualizar Professor

                </button>

            </div>

        </div>

    </form>

</div>
@endsection