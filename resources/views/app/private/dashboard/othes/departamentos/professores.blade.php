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

             @hasanyrole('coordenador|secretaria|admin')
          <a href="{{ route('professores.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center">
            <i class="fas fa-plus mr-2"></i> Novo Professor
        </a>
        
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
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center text-slate-600 font-bold shadow-sm">
                                    {{ substr($prof->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $prof->user->name }}</p>
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


                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-0 translate-x-2">
                                <a href="{{ route('professores.edit', $prof->id) }}"
                                   class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors shadow-sm" title="Editar">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>

                                <form method="POST" action="{{ route('professores.destroy', $prof->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 bg-red-50 text-red-500 hover:bg-red-600 hover:text-white rounded-lg transition-colors shadow-sm" 
                                            onclick="return confirm('Tem certeza que deseja eliminar este professor?')" title="Eliminar">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
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

        @if(method_exists($professores, 'links'))
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $professores->links() }}
            </div>
        @endif
    </div>
</div>
@endsection