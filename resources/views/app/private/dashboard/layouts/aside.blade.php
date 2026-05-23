 <div class="p-8 border-b border-blue-800 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold tracking-tighter flex items-center text-white">
            <i class="fas fa-graduation-cap mr-3 text-blue-400"></i>
            IP ACADÉMICO
        </h1>
        <p class="text-[10px] text-blue-300 uppercase tracking-widest mt-1">Gestão de Anteprojeto e TCC</p>
    </div>

    {{-- 🔥 NOVO ÍCONE DE NOTIFICAÇÕES SUPERIOR COM CONTADOR --}}
    <a href="{{ route('notifications.index') }}" class="relative p-2.5 bg-blue-800/40 hover:bg-blue-800 text-blue-200 hover:text-white rounded-xl transition group">
        <i class="fas fa-bell text-lg"></i>
        @if(Auth::user()->unreadNotifications->count() > 0)
            <span class="absolute -top-1 -right-1 bg-rose-500 text-white font-black text-[9px] h-5 w-5 rounded-full flex items-center justify-center border-2 border-blue-900 shadow-sm animate-pulse">
                {{ Auth::user()->unreadNotifications->count() }}
            </span>
        @endif
    </a>
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
            <a href="{{ route('estudante.relatorios.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Relatorios</a>
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
            <a href="{{route('professor.relatorios.index')}}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Relatorios</a>
        </div>
    </div>
    @endrole

    @hasanyrole('coordenador|admin')
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
             
             @hasanyrole('coordenador')
                <a href="{{ route('coordenador.relatorios.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Relatorio Geral</a>
             @endhasanyrole
            
             @hasanyrole('admin')
                <a href="{{ route('coordenadores.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Coordenadores</a>
                <a href="{{ route('areasCientificas.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Áreas Científicas</a>
                <a href="{{ route('itensMelhoria.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Itens Melhorias</a>
                <a href="{{ route('admin.relatorios.geral') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Relatório Geral</a>
             @endhasanyrole
        </div>
    </div>
    @endhasanyrole

    {{-- ITEM DE NOTIFICAÇÕES COM BADGE ALINHADO NO MENU --}}
    <div class="space-y-1">
        <button @click="openMenu = (openMenu === 'professor2' ? null : 'professor2')" 
                class="w-full flex items-center justify-between p-3 text-blue-200 hover:bg-blue-800 rounded-xl transition">
            <div class="flex items-center">
                <i class="fas fa-bell w-6 text-center text-blue-400"></i>
                <span class="ml-3 font-medium">Notificações</span>
            </div>
            <div class="flex items-center gap-2">
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-md">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="openMenu === 'professor2' ? 'rotate-180' : ''"></i>
            </div>
        </button>
        
        <div x-show="openMenu === 'professor2'" x-collapse x-cloak class="pl-10 space-y-1">
            <a href="{{ route('notifications.index') }}" class="block p-2 text-sm text-blue-300 hover:text-white transition">Todas Notificações</a>
        </div>
    </div>

</nav>

<div class="p-6 bg-blue-950 border-t border-blue-800">
    <div class="flex items-center">
        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center font-bold ring-2 ring-blue-400 text-white shrink-0">
            @role('estudante')  
                <a href="{{route('profile.estudante.edit')}}">{{ substr(Auth::user()->name, 0, 2) }}</a>
            @endrole
            @role('professor')
                <a href="{{route('profile.professor.edit')}}">{{ substr(Auth::user()->name, 0, 2) }}</a> 
            @endrole
            @role('admin')
                <a href="{{route('profile.admin.edit')}}">{{ substr(Auth::user()->name, 0, 2) }}</a> 
            @endrole
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