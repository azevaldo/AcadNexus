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
                <p class="text-sm text-gray-500 font-medium border-r pr-3 border-gray-300 shrink-0">
                    Gestão de matriculados
                </p>

                {{-- Área de Badges de Filtros Ativos --}}
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Filtros Ativos:</span>
                    
                    @if(request('nome'))
                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border">
                            Nome: "{{ request('nome') }}"
                        </span>
                    @endif

                    @if(request('email'))
                        <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border">
                            Email: "{{ request('email') }}"
                        </span>
                    @endif

                    @if(request('regime'))
                        <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border border-amber-200 shadow-sm">
                            Regime: {{ request('regime') }}
                        </span>
                    @endif

                    @if(request('ano'))
                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border border-blue-200 shadow-sm">
                            Ano: {{ request('ano') }}º
                        </span>
                    @endif

                    @if(request('tutor_status'))
                        <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border border-indigo-200 shadow-sm">
                            Tutor: {{ request('tutor_status') == 'com_tutor' ? 'Vinculado' : 'Não Vinculado' }}
                        </span>
                    @endif

                    @if(request('cotutor_status'))
                        <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border border-purple-200 shadow-sm">
                            Co-Tutor: {{ request('cotutor_status') == 'com_cotutor' ? 'Com Co-tutor' : 'Sem Co-tutor' }}
                        </span>
                    @endif

                    @if(!request('nome') && !request('email') && !request('regime') && !request('ano') && !request('tutor_status') && !request('cotutor_status'))
                        <span class="bg-gray-100 text-gray-400 text-[10px] px-2.5 py-1 rounded-lg font-bold uppercase border border-gray-200 italic">
                            Nenhum filtro aplicado
                        </span>
                    @endif

                    <button @click="openFilterModal = true" 
                            class="ml-2 flex items-center gap-2 px-3 py-1 bg-slate-800 hover:bg-slate-700 text-white text-[10px] font-bold uppercase rounded-lg transition shadow-sm">
                        <i class="fas fa-filter text-[9px]"></i> Configurar Filtros
                    </button>

                    @if(request('nome') || request('email') || request('regime') || request('ano') || request('tutor_status') || request('cotutor_status'))
                        <a href="{{ route('cursos.estudantes', $curso->id) }}" 
                           class="text-[10px] text-red-500 hover:text-red-700 font-bold uppercase underline decoration-2 underline-offset-4 transition ml-2">
                            Limpar Tudo
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
    <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-gray-600 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="px-6 py-4 text-left">Nome</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Matrícula</th>
                        <th class="px-6 py-4 text-left">Regime / Ano</th>
                        <th class="px-6 py-4 text-left">Orientadores</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($estudantes as $estudante)
                    <tr class="transition border-transparent border-l-4 {{ $estudante->user->acesso ? 'hover:bg-gray-50 hover:border-l-blue-500' : 'bg-gray-100/70 opacity-65 grayscale-[30%]' }}">
                        
                        <td class="px-6 py-4 font-medium {{ $estudante->user->acesso ? 'text-gray-800' : 'text-gray-400 line-through' }}">
                            {{ $estudante->user->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-600" x-data="{ revealed: false }">
                            <span @click="revealed = !revealed" class="cursor-pointer font-mono text-xs hover:text-blue-600 transition">
                                <template x-if="!revealed">
                                    <span>{{ Str::mask($estudante->user->email, '*', 3, 8) }}</span>
                                </template>
                                <template x-if="revealed">
                                    <span>{{ $estudante->user->email }}</span>
                                </template>
                            </span>
                        </td>

                        <td class="px-6 py-4 text-gray-600 font-mono text-xs" x-data="{ revealed: false }">
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
                            <div class="text-gray-700 font-semibold text-xs">{{ $estudante->regime }}</div>
                            <div class="text-gray-400 text-[11px]">{{ $estudante->ano_frequencia }}º Ano</div>
                        </td>

                        {{-- Coluna de Orientadores (Badges Inteligentes) --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                @if($estudante->tutorRelacao && $estudante->tutorRelacao->ativo)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100 max-w-max">
                                        <i class="fas fa-user-tie text-[9px]"></i> T: {{ Str::words($estudante->tutorRelacao->tutor->user->name, 2, '') }}
                                    </span>
                                    @if($estudante->tutorRelacao->coTutor)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-100 max-w-max">
                                            <i class="fas fa-user-friends text-[9px]"></i> CT: {{ Str::words($estudante->tutorRelacao->coTutor->user->name, 2, '') }}
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-400 bg-gray-50 px-2 py-0.5 rounded-md border border-dashed border-gray-200 max-w-max italic">
                                        Sem Orientação
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($estudante->user->acesso)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">
                                    <span class="w-1 h-1 rounded-full bg-green-500"></span> Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">
                                    <span class="w-1 h-1 rounded-full bg-red-400"></span> Suspenso
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex justify-end gap-2">
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
                                    <i class="fas fa-id-card text-xs"></i>
                                </button>
                                
                                <a href="{{ route('estudantes.show', $estudante->id) }}" 
                                   class="inline-flex items-center justify-center p-2 text-gray-500 bg-gray-50 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border" 
                                   title="Ver Detalhes do Perfil">
                                    <i class="fas fa-info-circle text-xs"></i>
                                </a>

                                <a href="{{ route('estudantes.edit', $estudante->id) }}"
                                   class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" title="Editar">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                @if($estudante->user->acesso)
                                    <button onclick="openModal('accessModal{{ $estudante->id }}')"
                                        class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Retirar Acesso">
                                        <i class="fas fa-user-slash text-xs"></i>
                                    </button>
                                @else
                                    <button onclick="openModal('accessModal{{ $estudante->id }}')"
                                        class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition" title="Conceder Acesso">
                                        <i class="fas fa-user-check text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50/50">
                            Nenhum estudante encontrado com estes critérios de filtragem.
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

    {{-- MODAL DE FILTROS AVANÇADO (Múltiplos Filtros Incorporados) --}}
    <div x-show="openFilterModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-cloak x-transition>
        
        <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden" @click.away="openFilterModal = false">
            <div class="p-6 bg-slate-900 text-white flex justify-between items-center">
                <div>
                    <h3 class="font-bold uppercase tracking-widest text-xs">Refinar Filtros Avançados</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Filtre por dados biográficos ou status de orientação científica</p>
                </div>
                <button @click="openFilterModal = false" class="text-white/50 hover:text-white transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('cursos.estudantes', $curso->id) }}" class="p-8 grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                {{-- Filtro Nome --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Nome do Estudante</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                        <input type="text" name="nome" value="{{ request('nome') }}" placeholder="Ex: Mário Silva"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                </div>

                {{-- Filtro Email --}}
                <div class="sm:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Email Institucional</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                        <input type="text" name="email" value="{{ request('email') }}" placeholder="Ex: estudante@universidade.com"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                    </div>
                </div>

                {{-- Filtro Regime --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Regime Académico</label>
                    <select name="regime" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Todos</option>
                        <option value="Regular" {{ request('regime') == 'Regular' ? 'selected' : '' }}>Regular</option>
                        <option value="Pós Laboral" {{ request('regime') == 'Pós Laboral' ? 'selected' : '' }}>Pós Laboral</option>
                    </select>
                </div>

                {{-- Filtro Ano --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Ano de Frequência</label>
                    <select name="ano" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Todos os Anos</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ request('ano') == $i ? 'selected' : '' }}>{{ $i }}º Ano</option>
                        @endfor
                    </select>
                </div>

                {{-- Filtro Tutor --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Tutor Principal</label>
                    <select name="tutor_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Todos</option>
                        <option value="com_tutor" {{ request('tutor_status') == 'com_tutor' ? 'selected' : '' }}>Com Tutor Atribuído</option>
                        <option value="sem_tutor" {{ request('tutor_status') == 'sem_tutor' ? 'selected' : '' }}>Sem Tutor Atribuído</option>
                    </select>
                </div>

                {{-- Filtro Co-Tutor --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5">Coorientador (Co-Tutor)</label>
                    <select name="cotutor_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <option value="">Todos</option>
                        <option value="com_cotutor" {{ request('cotutor_status') == 'com_cotutor' ? 'selected' : '' }}>Com Co-Tutor</option>
                        <option value="sem_cotutor" {{ request('cotutor_status') == 'sem_cotutor' ? 'selected' : '' }}>Sem Co-Tutor</option>
                    </select>
                </div>

                <div class="sm:col-span-2 flex gap-3 pt-4 border-t">
                    <button type="button" @click="openFilterModal = false" class="flex-1 px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-xs uppercase transition hover:bg-slate-200">
                        Fechar
                    </button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-bold text-xs uppercase shadow-lg shadow-blue-100 transition hover:bg-blue-700">
                        Filtrar Registos
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DE INFORMAÇÕES COMPLETAS (Mantido sem alterações) --}}
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

    {{-- MODAL ÚNICO DE ALTERAÇÃO DE ACESSO (DINÂMICO) --}}
    @foreach($estudantes as $estudante)
    <div id="accessModal{{ $estudante->id }}" class="fixed inset-0 hidden items-center justify-center bg-black/50 backdrop-blur-sm z-50 p-4">
        <div class="bg-white w-full max-w-md rounded-2xl p-6 shadow-xl animate-fadeIn">
            
            @if($estudante->user->acesso)
                <h2 class="text-xl font-bold text-gray-900 mb-3">Bloquear Acesso</h2>
                <p class="text-gray-600 mb-6">Tem certeza que deseja retirar o acesso do estudante <strong>{{ $estudante->user->name }}</strong>?</p>
            @else
                <h2 class="text-xl font-bold text-gray-900 mb-3">Restaurar Acesso</h2>
                <p class="text-gray-600 mb-6">Deseja conceder novamente o acesso ao sistema para o estudante <strong>{{ $estudante->user->name }}</strong>?</p>
            @endif
           
            <form method="POST" action="{{ route('estudantes.toggleAcesso', $estudante->id) }}">
                @csrf
                @method('PATCH')
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('accessModal{{ $estudante->id }}')" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                        Cancelar
                    </button>
                    
                    @if($estudante->user->acesso)
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
    if(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}
function closeModal(id){
    const modal = document.getElementById(id);
    if(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>
@endsection