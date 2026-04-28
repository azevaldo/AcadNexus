@extends('app.private.dashboard.app..templete')
@section('title','Estudantes')

@section('content')
<div class="p-8">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Camapnha :  {{ $campanha->titulo }}</h1>
            <p class="text-sm text-gray-500">Lista de estudante</p>
        </div>
 @hasanyrole('admin|coordenador|secretaria')
       <button
onclick="openModal('addStudentModal')"
class="bg-gradient-to-r from-blue-600 to-indigo-600
text-white px-6 py-3 rounded-xl font-semibold shadow">

+ Novo Estudante

</button>
@endhasanyrole
    </div>


    <!-- TABELA -->
    <div class="bg-white rounded-2xl shadow overflow-hidden border">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left">Nome</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-left">Curso</th>
                    <th class="px-6 py-4 text-left">Matrícula</th>
                    <th class="px-6 py-4 text-left">Regime</th>
                    <th class="px-6 py-4 text-right">Ações</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @foreach($estudantes as $estudante)

                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium">
                        {{ $estudante->user->name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $estudante->user->email }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $estudante->curso->nome }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $estudante->numero_matricula }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $estudante->regime }}
                    </td>

                    <td class="px-6 py-4 text-right space-x-3">
 @hasanyrole('admin|coordenador|secretaria')
         <button onclick="openModal('removeStudentModal{{ $estudante->id }}')"
class="text-red-600 hover:text-red-800 text-lg"
title="Desassociar da campanha">

❌

</button>
@endhasanyrole
                    </td>

                </tr>

<!-- MODAL DESASSOCIAR -->
<div id="removeStudentModal{{ $estudante->id }}"
class="fixed inset-0 hidden items-center justify-center
bg-black/50 backdrop-blur-sm z-50">

<div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl">

<h2 class="text-xl font-bold mb-3 text-gray-800">
Desassociar Estudante
</h2>

<p class="text-gray-600 mb-6">

Tem certeza que deseja desassociar o estudante

<strong>{{ $estudante->user->name }}</strong>

da campanha

<strong>{{ $campanha->titulo }}</strong> ?

</p>

<form method="POST"
action="{{ route('campanhas.estudantes.destroy',[$campanha->id,$estudante->id]) }}">

@csrf
@method('DELETE')

<div class="flex justify-end gap-3">

<button type="button"
onclick="closeModal('removeStudentModal{{ $estudante->id }}')"
class="px-4 py-2 bg-gray-200 rounded-lg">

Cancelar

</button>

<button type="submit"
class="px-5 py-2 bg-red-600 text-white rounded-lg">

Desassociar

</button>

</div>

</form>

</div>

</div>

                @endforeach

            </tbody>
        </table>

    </div>


    <div class="mt-6">
        {{ $estudantes->links() }}
    </div>

</div>
<!-- MODAL ADICIONAR ESTUDANTE -->
<div id="addStudentModal"
class="fixed inset-0 hidden items-center justify-center
bg-black/40 backdrop-blur-sm z-50">

<div class="bg-white w-full max-w-lg rounded-2xl p-8 shadow-2xl">

<h2 class="text-2xl font-bold mb-2">
Adicionar Estudante
</h2>

<p class="text-gray-500 text-sm mb-6">
Digite o email do estudante para adicioná-lo na campanha
</p>

<form method="POST"
action="{{ route('campanhas.estudantes.store',$campanha->id) }}">

@csrf

<div class="mb-6">

<label class="block text-sm font-semibold text-gray-600 mb-2">
Email do Estudante
</label>

<input type="email"
name="email"
placeholder="exemplo@email.com"
required
class="w-full border rounded-xl px-4 py-3
focus:ring-2 focus:ring-blue-500 focus:outline-none">

</div>


<div class="flex justify-end gap-3">

<button type="button"
onclick="closeModal('addStudentModal')"
class="px-5 py-2 bg-gray-200 rounded-lg">

Cancelar

</button>

<button type="submit"
class="px-6 py-2 bg-blue-600 text-white rounded-lg">

Adicionar

</button>

</div>

</form>

</div>

</div>

<script>
function openModal(id){
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal(id){
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

@endsection