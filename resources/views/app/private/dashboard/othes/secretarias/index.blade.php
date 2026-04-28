 @extends('app.private.dashboard.app..templete')

@section('title','Secretarias')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Secretarias</h1>
            <p class="text-sm text-gray-500">Usuários com permissão de secretaria</p>
        </div>
        <a href="{{ route('secretarias.create') }}"
           class="bg-blue-600 text-white px-5 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
            + Nova Secretaria
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Nome</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Email</th>
                    <th class="px-6 py-4 text-right font-semibold text-gray-600">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($secretarias as $sec)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $sec->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $sec->email }}</td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Ativo</span>
                        
                        <button onclick="openModal('deleteModal{{ $sec->id }}')" 
                                class="text-red-600 hover:text-red-800 transition" 
                                title="Eliminar">
                            🗑️
                        </button>
                    </td>
                </tr>

                <div id="deleteModal{{ $sec->id }}"
                     class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-">
                    
                    <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl text-left">
                        <h2 class="text-xl font-bold mb-3 text-gray-800">Eliminar Secretaria</h2>
                        <p class="text-gray-600 mb-6">
                            Tem certeza que deseja eliminar o acesso de 
                            <strong>{{ $sec->name }}</strong>? Esta ação não pode ser desfeita.
                        </p>

                        <form method="POST" action="{{ route('secretarias.destroy', $sec->id) }}">
                            @csrf
                            @method('DELETE')

                            <div class="flex justify-end gap-3">
                                <button type="button" onclick="closeModal('deleteModal{{ $sec->id }}')"
                                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                                    Confirmar Eliminação
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $secretarias->links() }}
    </div>
</div>

<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Fechar modal ao clicar fora dele
    window.onclick = function(event) {
        if (event.target.classList.contains('fixed')) {
            event.target.classList.add('hidden');
            event.target.classList.remove('flex');
        }
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out forwards;
    }
</style>

@endsection