@extends('app.private.dashboard.app.templete')

@section('title', 'Meus Departamentos')

@section('content')
<div class="p-8">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Meus Departamentos</h1>
            <p class="text-gray-500 text-sm mt-1">
                Departamentos aos quais você está vinculado
            </p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-8 py-4 text-left font-semibold text-gray-600 uppercase tracking-wider">
                        Nome do Departamento
                    </th>
                    <th class="px-8 py-4 text-right font-semibold text-gray-600 uppercase tracking-wider">
                        Consultar
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($departamentos as $departamento)
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-8 py-5 font-medium text-gray-800">
                        {{ $departamento->nome }}
                    </td>

                    <td class="px-8 py-5 text-right">
                        <div class="flex justify-end items-center gap-4">

                            <a href="{{ route('departamento.cursos', ['departamento'=>$departamento->id,'privado'=>true]) }}"
                               class="text-gray-500 hover:text-amber-600 transition flex items-center gap-1"
                               title="Ver Cursos">
                                <i class="fas fa-graduation-cap"></i>
                                <span class="text-xs font-normal hidden sm:inline">Cursos</span>
                            </a>
            @hasanyrole('coordenador|admin')
                            <a href="{{ route('departamentos.professores', $departamento->id) }}"
                               class="text-gray-500 hover:text-indigo-600 transition flex items-center gap-1"
                               title="Ver Professores">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <span class="text-xs font-normal hidden sm:inline">Professores</span>
                            </a>
                            @endhasanyrole

                        </div>
                    </td>        
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="px-8 py-12 text-center text-gray-500">
                        <i class="fas fa-folder-open text-3xl mb-3 block text-gray-300"></i>
                        Você não está vinculado a nenhum departamento atualmente.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $departamentos->links() }}
    </div>

</div>
@endsection