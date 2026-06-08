 @extends('app.private.dashboard.app.templete')

@section('title', 'Professores')

@section('content')
<div class="p-8 max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                @if(isset($departamento))
                    Professores: <span class="text-blue-600">{{ $departamento->nome }}</span>
                @else
                    Lista de Professores
                @endif
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                @if(isset($departamento))
                    Exibindo docentes vinculados ao departamento de <strong>{{ $departamento->nome }}</strong>.
                @else
                    Gerencie os docentes e as atribuições de tutoria global da instituição.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if(isset($departamento))
                <a href="{{ route('departamentos.index') }}" 
                   class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-6 py-3 rounded-2xl font-bold transition-all flex items-center border border-slate-200">
                    <i class="fas fa-arrow-left mr-2"></i> Voltar
                </a>
            @endif

            @hasanyrole('coordenador|admin')

            @role('admin')
                                    <a href="{{ route('professores.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center">
                    <i class="fas fa-plus mr-2"></i> Novo Professor
                </a>
             @endrole

                   @if(auth()->user()->ehCoordenadorDoDepartamento($departamento->id))
                <a href="{{ route('professores.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center">
                    <i class="fas fa-plus mr-2"></i> Novo Professor
                </a>
                   @endif

            @endhasanyrole
        </div>
    </div>

    <div class="bg-white shadow-sm border border-slate-100 rounded-[2rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-black uppercase text-slate-400 tracking-widest">
                        <th class="px-6 py-5">Nome / Email</th>
                        <th class="px-6 py-5">Departamento / Áreas</th>
                        <th class="px-6 py-5 text-center">Estatuto</th>
                        <th class="px-6 py-5 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($professores as $prof)
                    {{-- FEEDBACK VISUAL: Linha opaca e cinza se o user correspondente estiver sem acesso --}}
                    <tr class="transition-colors group {{ $prof->user->acesso ? 'hover:bg-slate-50/30' : 'bg-gray-100/70 opacity-65 grayscale-[30%]' }}">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center text-slate-600 font-bold shadow-sm">
                                    {{ substr($prof->user->name, 0, 1) }}
                                </div>
                                <div>
                                    {{-- Nome riscado se não tiver acesso --}}
                                    <p class="text-sm font-bold {{ $prof->user->acesso ? 'text-slate-800' : 'text-slate-400 line-through' }}">
                                        {{ $prof->user->name }}
                                    </p>
                                    <p class="text-[11px] text-slate-400 font-medium">{{ $prof->user->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1 mb-1">
                                @foreach($prof->departamentos as $d)
                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-[10px] font-bold border border-slate-200">
                                        {{ $d->nome }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="flex flex-wrap gap-1">
                                @foreach($prof->areas as $a)
                                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase">
                                        {{ $a->nome }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        {{-- COLUNA DE STATUS VISUAL --}}
                        <td class="px-6 py-5 text-center">
                            @if($prof->user->acesso)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                    <span class="w-1 h-1 rounded-full bg-green-500"></span> Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">
                                    <span class="w-1 h-1 rounded-full bg-red-400"></span> Suspenso
                                </span>
                            @endif
                        </td>
 <td class="px-6 py-5 text-right">

    <div class="flex items-center justify-end gap-2
        {{ $prof->user->acesso ? 'opacity-0 group-hover:opacity-100' : 'opacity-100' }}
        transition-all">

<a href="{{ route('professores.show', $prof->id) }}"
   class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition"
   title="Ver Perfil">
    <i class="fas fa-user"></i>
</a>
           @if(auth()->user()->ehCoordenadorDoDepartamento($departamento->id))
        <!-- EDITAR -->
        <a href="{{ route('professores.edit', $prof->id) }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg
                  text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition"
           title="Editar">

            <i class="fas fa-pen-to-square"></i>

        </a>

        <!-- BLOQUEAR / ATIVAR -->
        <button onclick="openAccessModal('{{ $prof->id }}')"
                class="w-9 h-9 flex items-center justify-center rounded-lg
                       text-slate-500
                       {{ $prof->user->acesso
                            ? 'hover:bg-amber-50 hover:text-amber-600'
                            : 'hover:bg-green-50 hover:text-green-600'
                       }}
                       transition"
                title="{{ $prof->user->acesso ? 'Bloquear Acesso' : 'Conceder Acesso' }}">

            <i class="fas {{ $prof->user->acesso ? 'fa-user-slash' : 'fa-user-check' }}"></i>

        </button>

        <!-- ELIMINAR -->
        <button onclick="openModal('deleteProfessor{{ $prof->id }}')"
                class="w-9 h-9 flex items-center justify-center rounded-lg
                       text-slate-500 hover:bg-red-50 hover:text-red-600 transition"
                title="Eliminar">

            <i class="fas fa-trash"></i>

        </button>

           @endif

           @role('admin')
        <!-- EDITAR -->
        <a href="{{ route('professores.edit', $prof->id) }}"
           class="w-9 h-9 flex items-center justify-center rounded-lg
                  text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition"
           title="Editar">

            <i class="fas fa-pen-to-square"></i>

        </a>

        <!-- BLOQUEAR / ATIVAR -->
        <button onclick="openAccessModal('{{ $prof->id }}')"
                class="w-9 h-9 flex items-center justify-center rounded-lg
                       text-slate-500
                       {{ $prof->user->acesso
                            ? 'hover:bg-amber-50 hover:text-amber-600'
                            : 'hover:bg-green-50 hover:text-green-600'
                       }}
                       transition"
                title="{{ $prof->user->acesso ? 'Bloquear Acesso' : 'Conceder Acesso' }}">

            <i class="fas {{ $prof->user->acesso ? 'fa-user-slash' : 'fa-user-check' }}"></i>

        </button>

       

           @endrole

    </div>

</td>
                    </tr>

                     <div id="confirmDelete{{ $prof->id }}"
         class="hidden absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-xl shadow-lg p-4 z-50">

        <p class="text-sm text-gray-600 mb-3">
            Eliminar <strong>{{ $prof->user->name }}</strong>?
        </p>

        <form method="POST"
              action="{{ route('professores.destroy', $prof->id) }}">

            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-2">

                <button type="button"
                    onclick="document.getElementById('confirmDelete{{ $prof->id }}').classList.add('hidden')"
                    class="px-3 py-1.5 text-sm bg-gray-100 rounded-lg">

                    Cancelar

                </button>

                <button type="submit"
                    class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-lg">

                    Eliminar

                </button>

            </div>

        </form>

    </div>
                    {{-- MODAL DINÂMICO DE CONFIRMAÇÃO DE ACESSO PARA CADA PROFESSOR --}}
                    <div id="accessModal{{ $prof->id }}" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-50 p-4">
                        <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl text-left">
                            @if($prof->user->acesso)
                                <h2 class="text-xl font-bold text-gray-900 mb-3">Bloquear Acesso</h2>
                                <p class="text-gray-600 mb-6">Tem certeza que deseja retirar o acesso do professor <strong>{{ $prof->user->name }}</strong>?</p>
                            @else
                                <h2 class="text-xl font-bold text-gray-900 mb-3">Restaurar Acesso</h2>
                                <p class="text-gray-600 mb-6">Deseja conceder novamente o acesso ao sistema para o professor <strong>{{ $prof->user->name }}</strong>?</p>
                            @endif

                            <form method="POST" action="{{ route('professores.toggleAcesso', $prof->id) }}"  onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
                                @csrf
                                @method('PATCH')
                                <div class="flex justify-end gap-3">
                                    <button type="button" onclick="closeAccessModal('{{ $prof->id }}')" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                                        Cancelar
                                    </button>
                                    @if($prof->user->acesso)
                                        <button type="submit" class="px-5 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                                            Bloquear
                                        </button>
                                    @else
                                        <button type="submit" class="px-5 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                                            Ativar Acesso
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-users-slash text-4xl text-slate-200 mb-4"></i>
                                <p class="text-slate-400 font-medium">Nenhum professor encontrado.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
 @if($professores->hasPages())
        <div class="px-6 py-5 bg-slate-50/50 border-t border-slate-100">
            {{ $professores->links() }}
        </div>
        @endif
 
    </div>
</div>

<script>
function openAccessModal(id) {
    const modal = document.getElementById('accessModal' + id);
    if(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeAccessModal(id) {
    const modal = document.getElementById('accessModal' + id);
    if(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>
@endsection