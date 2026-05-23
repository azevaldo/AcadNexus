 @extends('app.private.dashboard.app.templete')

@section('title', 'Estudantes')

@section('content')
<div class="p-8" x-data="{ openInfoModal: false, openFilterModal: false, selectedStudent: {} }">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                @if(isset($curso))
                    Estudantes: <span class="text-blue-600">{{ $curso->nome }}</span>
                @else
                    Estudantes
                @endif
            </h1>
            
            <div class="flex flex-wrap items-center gap-3 mt-2">
                <p class="text-sm text-gray-500 font-medium border-r pr-3 border-gray-300">
                    Gestão de matriculados
                </p>

                {{-- Área de Filtros Ativos --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Filtros:</span>
                    
                    {{-- Badge de Regime --}}
                    @if(request('regime'))
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border border-amber-200 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Regime: {{ request('regime') }}
                        </span>
                    @else
                        <span class="bg-gray-100 text-gray-400 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border border-gray-200 italic">
                            Regime: Todos
                        </span>
                    @endif

                    {{-- Badge de Ano de Frequência --}}
                    @if(request('ano'))
                        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border border-blue-200 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            Ano: {{ request('ano') }}º
                        </span>
                    @else
                        <span class="bg-gray-100 text-gray-400 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border border-gray-200 italic">
                            Ano: Todos
                        </span>
                    @endif

                    {{-- Botão para Abrir Filtro (Aqui mesmo na tela) --}}
                    <button @click="openFilterModal = true" 
                            class="ml-2 flex items-center gap-2 px-3 py-1 bg-slate-800 hover:bg-slate-700 text-white text-[10px] font-bold uppercase rounded-lg transition shadow-sm">
                        <i class="fas fa-filter text-[9px]"></i> Filtrar Lista
                    </button>

                    {{-- Botão para Limpar --}}
                    @if(request('regime') || request('ano'))
                        <a href="{{ route('cursos.estudantes', $curso->id) }}" 
                           class="text-[10px] text-red-500 hover:text-red-700 font-bold uppercase underline decoration-2 underline-offset-4 transition ml-2">
                            Limpar
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if(isset($curso))
            <a href="{{ route('departamento.cursos', $departamento->id) }}" 
               class="bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center border border-gray-200 shadow-sm">
                <i class="fas fa-arrow-left mr-2 text-xs text-gray-400"></i> Voltar
            </a>
        @endif
    </div>

    {{-- Tabela de Estudantes --}}
    <div class="bg-white rounded-2xl shadow border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-gray-600 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="px-6 py-4 text-left">Nome</th>
                        <th class="px-6 py-4 text-left">Email (Clique p/ ver)</th>
                        <th class="px-6 py-4 text-left">Curso</th>
                        <th class="px-6 py-4 text-left">Matrícula</th>
                        <th class="px-6 py-4 text-left">Regime</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($estudantes as $estudante)
                    <tr class="hover:bg-gray-50 transition border-transparent border-l-4 hover:border-l-blue-500">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $estudante->user->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-600" x-data="{ revealed: false }">
                            <span @click="revealed = !revealed" class="cursor-pointer font-mono hover:text-blue-600 transition">
                                <template x-if="!revealed">
                                    <span>{{ Str::mask($estudante->user->email, '*', 3, 8) }}</span>
                                </template>
                                <template x-if="revealed">
                                    <span>{{ $estudante->user->email }}</span>
                                </template>
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-[10px] font-black uppercase">
                                {{ $estudante->curso->nome }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-gray-600 font-mono" x-data="{ revealed: false }">
                            <span @click="revealed = !revealed" class="cursor-pointer hover:text-blue-600 transition">
                                <template x-if="!revealed">
                                    <span>****{{ substr($estudante->numero_matricula, -3) }}</span>
                                </template>
                                <template x-if="revealed">
                                    <span>{{ $estudante->numero_matricula }}</span>
                                </template>
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-gray-600 text-[11px] font-semibold italic">{{ $estudante->regime }}</span>
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-3">
                                <button 
                                    @click="selectedStudent = {
                                        name: '{{ $estudante->user->name }}',
                                        email: '{{ $estudante->user->email }}',
                                        matricula: '{{ $estudante->numero_matricula }}',
                                        bi: '{{ $estudante->bi }}',
                                        ano: '{{ $estudante->ano_frequencia }}º Ano',
                                        regime: '{{ $estudante->regime }}',
                                        curso: '{{ $estudante->curso->nome }}'
                                    }; openInfoModal = true"
                                    class="p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition" title="Dados Completos">
                                    <i class="fas fa-id-card"></i>
                                </button>

                                <a href="{{ route('estudantes.edit',$estudante->id) }}"
                                   class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button onclick="openModal('deleteModal{{ $estudante->id }}')"
                                    class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                            Nenhum estudante encontrado com estes filtros.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginação --}}
    <div class="mt-6">
        {{ $estudantes->appends(request()->query())->links() }}
    </div>

    {{-- MODAL DE FILTRO (Alpine.js) --}}
    <div x-show="openFilterModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-cloak x-transition>
        
        <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden" @click.away="openFilterModal = false">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="font-bold uppercase tracking-widest text-xs">Refinar Pesquisa</h3>
                <button @click="openFilterModal = false" class="text-white/50 hover:text-white transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('cursos.estudantes', $curso->id) }}" class="p-8 space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Regime Académico</label>
                    <select name="regime" class="w-full bg-slate-50 border border-slate-100 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Todos os Regimes</option>
                        <option value="Regular" {{ request('regime') == 'Regular' ? 'selected' : '' }}>Regular</option>
                        <option value="Pós Laboral" {{ request('regime') == 'Pós Laboral' ? 'selected' : '' }}>Pós Laboral</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Ano de Frequência</label>
                    <select name="ano" class="w-full bg-slate-50 border border-slate-100 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Todos os Anos</option>
                        @for ($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ request('ano') == $i ? 'selected' : '' }}>{{ $i }}º Ano</option>
                        @endfor
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openFilterModal = false" class="flex-1 px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs uppercase transition hover:bg-slate-200">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold text-xs uppercase shadow-lg shadow-blue-100 transition hover:bg-blue-700">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DE INFORMAÇÕES COMPLETAS (Alpine.js) --}}
    <div x-show="openInfoModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-cloak x-transition>
        
        <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden" @click.away="openInfoModal = false">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center">
                <h3 class="font-bold uppercase tracking-widest text-sm">Ficha do Estudante</h3>
                <button @click="openInfoModal = false" class="text-white/50 hover:text-white transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-8 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Nome Completo</p>
                        <p class="font-bold text-slate-800" x-text="selectedStudent.name"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">E-mail</p>
                        <p class="font-mono text-xs text-slate-800" x-text="selectedStudent.email"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Matrícula</p>
                        <p class="font-mono text-slate-800" x-text="selectedStudent.matricula"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">BI / Documento</p>
                        <p class="font-bold text-slate-800" x-text="selectedStudent.bi"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Ano de Frequência</p>
                        <p class="font-bold text-slate-800" x-text="selectedStudent.ano"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Curso</p>
                        <p class="font-bold text-blue-600" x-text="selectedStudent.curso"></p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Regime</p>
                        <p class="font-bold text-slate-800" x-text="selectedStudent.regime"></p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 bg-slate-50 text-right">
                <button @click="openInfoModal = false" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-800 transition">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    {{-- Modais de Exclusão (JS Puro) --}}
    @foreach($estudantes as $estudante)
    <div id="deleteModal{{ $estudante->id }}" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z- p-4">
        <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl animate-fadeIn">
            <h2 class="text-xl font-bold mb-3">Eliminar Estudante</h2>
            <p class="text-gray-600 mb-6">Deseja eliminar <strong>{{ $estudante->user->name }}</strong>?</p>
            <form method="POST" action="{{ route('estudantes.destroy',$estudante->id) }}">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('deleteModal{{ $estudante->id }}')" class="px-4 py-2 bg-gray-200 rounded-lg">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-lg">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

</div>

<style>
    [x-cloak] { display: none !important; }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn { animation: fadeIn 0.2s ease-out; }
</style>

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