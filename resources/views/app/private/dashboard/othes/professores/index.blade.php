@extends('app.private.dashboard.app.templete')
@section('title','Professores')

@section('content')
<div class="p-8 max-w-7xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Lista de Professores</h1>
            <p class="text-slate-500 text-sm">Gerencie os docentes e as atribuições de tutoria.</p>
        </div>
 @hasanyrole('coordenador|secretaria|admin')
                <a href="{{ route('professores.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-200 transition-all flex items-center">
            <i class="fas fa-plus mr-2"></i> Novo Professor
        </a>
        
        @endhasanyrole
    </div>

    <div class="bg-white shadow-sm border border-slate-100 rounded-[2rem] overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-black uppercase text-slate-400 tracking-widest">
                    <th class="px-6 py-5 text-left">Nome / Email</th>
                    <th class="px-6 py-5 text-left">Departamento/Áreas</th>
                    <th class="px-6 py-5 text-center">Estatuto</th>
                    <th class="px-6 py-5 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($professores as $prof)
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 font-bold">
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
                            @foreach($prof->departamentos->take(1) as $d)
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md text-[10px] font-bold">
                                    {{ $d->nome }}
                                </span>
                            @endforeach
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($prof->areas->take(1) as $a)
                                <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase">
                                    {{ $a->nome }}
                                </span>
                            @endforeach
                        </div>
                    </td>

                    <td class="px-6 py-5 text-center">
                        <form action="{{ route('tutores.toggle', $prof->id) }}" method="POST">
                            @csrf
                            @if($prof->tutor)
                                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase border border-emerald-100 hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all group/btn">
                                    <i class="fas fa-check-circle group-hover/btn:hidden"></i>
                                    <i class="fas fa-times-circle hidden group-hover/btn:inline"></i>
                                    Tutor Ativo
                                </button>
                            @else
                                <button type="submit" class="inline-flex items-center gap-2 bg-slate-50 text-slate-400 px-4 py-1.5 rounded-full text-[10px] font-black uppercase border border-slate-100 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fas fa-graduation-cap"></i>
                                    Tornar Tutor
                                </button>
                            @endif
                        </form>
                    </td>

                    <td class="px-6 py-5 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                            <a href="{{ route('professores.edit',$prof->id) }}"
                               class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors shadow-sm" title="Editar">
                                <i class="fas fa-edit text-xs"></i>
                            </a>

                            <form method="POST" action="{{ route('professores.destroy',$prof->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 bg-red-50 text-red-500 hover:bg-red-600 hover:text-white rounded-lg transition-colors shadow-sm" 
                                        onclick="return confirm('Tem certeza que deseja eliminar este professor?')" title="Eliminar">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection