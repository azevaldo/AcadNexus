@extends('app.private.dashboard.app.templete')

@section('content')
<main class="flex-1 flex flex-col overflow-hidden bg-gray-50/50">
    {{-- Header de Super Admin --}}
    <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm">
        <div class="flex items-center">
            <div class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter mr-4">
               Modo Admin
            </div>
            <h2 class="text-sm font-bold text-gray-500">Painel de Controle Central</h2>
        </div>
        
        <div class="flex items-center space-x-4">
            <span class="text-xs font-medium text-gray-400">{{ now()->format('d/m/Y H:i') }}</span>
            <div class="h-10 w-10 rounded-full bg-gray-900 flex items-center justify-center text-white">
              <a href="{{route('profile.admin.edit')}}"> <i class="fas fa-cog animate-spin-slow"></i></a> 
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 space-y-8">
        
        {{-- Top Stats: Foco em Infraestrutura --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase">Utilizadores</p>
                        <h4 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['total_utilizadores'] }}</h4>
                    </div>
                    <i class="fas fa-users text-indigo-200 text-xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase">Cursos Ativos</p>
                        <h4 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['total_cursos'] }}</h4>
                    </div>
                    <i class="fas fa-university text-blue-200 text-xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase">Total Anteprojetos</p>
                        <h4 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['total_projetos'] }}</h4>
                    </div>
                    <i class="fas fa-file-alt text-green-200 text-xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase">Campanhas</p>
                        <h4 class="text-3xl font-black text-gray-800 mt-1">{{ $stats['campanhas_ativas'] }}</h4>
                    </div>
                    <i class="fas fa-clock text-red-200 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Lista de Usuários Recentes --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/10">
                        <h3 class="font-black text-gray-800 text-lg uppercase tracking-tighter">Últimos Registos</h3>
                        <a href="{{ route('admin.usuarios.recentes') }}" class="text-xs font-bold text-indigo-600 hover:underline">Gerenciar todos</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-widest">
                                <tr>
                                    <th class="px-8 py-4">Nome</th>
                                    <th class="px-8 py-4">E-mail</th>
                                    <th class="px-8 py-4">Nível</th>
                                    <th class="px-8 py-4">Data</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($recentUsers as $u)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-8 py-4">
                                        <p class="text-sm font-bold text-gray-800">{{ $u->name }}</p>
                                    </td>
                                    <td class="px-8 py-4">
                                        <p class="text-xs text-gray-500">{{ $u->email }}</p>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="px-2 py-1 rounded-md bg-gray-100 text-[9px] font-black uppercase text-gray-600">
                                            {{ $u->roles->first()->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-xs text-gray-400">
                                        {{ $u->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

           

                {{-- Performance por Curso --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                    <h4 class="font-black text-xs uppercase tracking-widest text-gray-400 mb-6">Projetos por Curso</h4>
                    <div class="space-y-4">
                        @foreach($cursosPopulares as $curso)
                        <div>
                            <div class="flex justify-between text-[10px] font-black uppercase mb-1">
                                <span class="text-gray-600">{{ $curso->sigla ?? substr($curso->nome, 0, 15) }}</span>
                                <span class="text-indigo-600">{{ $curso->anteprojetos_count }}</span>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-indigo-500 h-full" style="width: {{ ($curso->anteprojetos_count / max(1, $stats['total_projetos'])) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection