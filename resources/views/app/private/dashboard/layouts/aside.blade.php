<div class="p-8 border-b border-blue-800">
    <h1 class="text-2xl font-bold tracking-tighter flex items-center text-white">
        <i class="fas fa-graduation-cap mr-3 text-blue-400"></i>
        IP ACADÉMICO
    </h1>
    <p class="text-[10px] text-blue-300 uppercase tracking-widest mt-1">Gestão de Anteprojeto e TCC</p>
</div>

<nav class="flex-1 overflow-y-auto py-6 px-4 space-y-3 custom-scrollbar" x-data="{ openMenu: null }">
    
    <div>
        <a href="{{ route('dashboard') }}" 
           class="flex items-center p-3 {{ request()->routeIs('dashboard') ? 'bg-blue-800 text-white shadow-md' : 'text-blue-200 hover:bg-blue-800/50' }} rounded-xl transition">
            <i class="fas fa-chart-line w-6 text-center"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>
    </div>

    @role('estudante')
    <div class="space-y-1">
        <button @click="openMenu = (openMenu === 'estudante' ? null : 'estudante')" 
                class="w-full flex items-center justify-between p-3 text-blue-200 hover:bg-blue-800 rounded-xl transition">
            <div class="flex items-center">
                <i class="fas fa-user-graduate w-6 text-center text-blue-400"></i>
                <span class="ml-3 font-medium">Minha Área</span>
            </div>
            <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="openMenu === 'estudante' ? 'rotate-180' : ''"></i>
        </button>
        
        <div x-show="openMenu === 'estudante'" x-collapse x-cloak class="pl-10 space-y-1">
            <a href="{{ route('estudante.campanhaestudante.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Campanhas Abertas</a>
            <a href="{{ route('estudante.acompanhamento.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Meu TCC / Progresso</a>
            <a href="{{ route('solicitacoes.estudante.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Historico Solicitações</a>
            <a href="{{ route('solicitacoes.create') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Enviar Solicitação</a>
        </div>
    </div>
    @endrole

    @role('professor')
    <div class="space-y-1">
        <button @click="openMenu = (openMenu === 'professor' ? null : 'professor')" 
                class="w-full flex items-center justify-between p-3 text-blue-200 hover:bg-blue-800 rounded-xl transition">
            <div class="flex items-center">
                <i class="fas fa-chalkboard-teacher w-6 text-center text-blue-400"></i>
                <span class="ml-3 font-medium">Orientação</span>
            </div>
            <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="openMenu === 'professor' ? 'rotate-180' : ''"></i>
        </button>
        
        <div x-show="openMenu === 'professor'" x-collapse x-cloak class="pl-10 space-y-1">
            <a href="{{ route('tutor.estudantes.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Meus Orientandos</a>
            <a href="{{route('professores.campanhas')}}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Avaliações</a>
            <a href="{{route('solicitacoes.tutor.index')}}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Solicitações Estudantes</a>
        </div>
    </div>
    @endrole

    @hasanyrole('coordenador|secretaria|admin')
    <div class="space-y-1">
        <button @click="openMenu = (openMenu === 'admin' ? null : 'admin')" 
                class="w-full flex items-center justify-between p-3 text-blue-200 hover:bg-blue-800 rounded-xl transition">
            <div class="flex items-center">
                <i class="fas fa-tools w-6 text-center text-blue-400"></i>
                <span class="ml-3 font-medium">Administração</span>
            </div>
            <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="openMenu === 'admin' ? 'rotate-180' : ''"></i>
        </button>
        
        <div x-show="openMenu === 'admin'" x-collapse x-cloak class="pl-10 space-y-1">
             
             <a href="{{ route('campanhas.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Campanhas</a>
                       
             <a href="{{ route('departamentos.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Departamentos</a>
            
            
            <a href="{{ route('cursos.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Cursos</a>
            <a href="{{ route('professores.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Professores</a>
            <a href="{{ route('estudantes.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Estudantes</a>
            @hasanyrole('secretaria|admin')
            <a href="{{ route('coordenadores.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Coordenadores</a>
            <a href="{{ route('areasCientificas.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Áreas Científicas</a>
            @endhasanyrole
            @hasanyrole('admin')
            <a href="{{ route('secretarias.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Secretaria</a>
         @endhasanyrole
        </div>
    </div>
    @endhasanyrole

    <div class="pt-4 border-t border-blue-800/50">
        <a href="#" class="flex items-center p-3 text-blue-300 hover:text-white transition italic text-sm group">
            <i class="fas fa-robot w-6 text-center group-hover:animate-pulse text-blue-400"></i>
            <span class="ml-3 font-medium">Assistente IA</span>
        </a>
    </div>

</nav>

<div class="p-6 bg-blue-950 border-t border-blue-800">
    <div class="flex items-center">
        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold ring-2 ring-blue-400 text-white shrink-0">
            {{ substr(Auth::user()->name, 0, 2) }}
        </div>
        <div class="ml-3 overflow-hidden">
            <p class="text-sm font-bold leading-none truncate text-white">{{ Auth::user()->name }}</p>
            <p class="text-[10px] text-blue-400 mt-1 uppercase font-semibold">
                {{ Auth::user()->roles->first()->name ?? 'Usuário' }}
            </p>
        </div>
        <button onclick="openLogoutModal()" class="ml-auto text-blue-400 hover:text-red-400 transition p-2">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </div>
</div>