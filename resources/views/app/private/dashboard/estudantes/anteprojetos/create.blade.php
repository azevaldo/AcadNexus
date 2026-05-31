@extends('app.private.dashboard.app.templete')

@section('title','Submeter Anteprojeto')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-12">

   {{-- Link Único de Navegação Superior --}}
<div class="mb-6">
    <a href="{{ route('estudante.anteprojetos.meuAnteprojeto', $campanha->id) }}" 
       class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors group">
        <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
        Voltar para Meu Anteprojeto
    </a>
</div>

{{-- Cabeçalho Centralizado --}}
<div class="text-center mb-12">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl mb-4 shadow-sm">
        <i class="fas fa-file-upload text-2xl"></i>
    </div>
    
    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
        Submeter Anteprojeto
    </h1>
    
    <p class="text-slate-500 text-base mt-2 max-w-md mx-auto">
        Preencha os detalhes abaixo para oficializar a submissão do seu trabalho acadêmico na campanha ativa.
    </p>
</div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">

        <form method="POST" action="{{ route('estudante.anteprojetos.store') }}" enctype="multipart/form-data"
        onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');">
            @csrf

            <div class="p-8 md:p-12 space-y-10">

               <div class="space-y-3">
    <label class="flex items-center text-sm font-bold text-slate-700 ml-1">
        <i class="fas fa-bullhorn mr-2 text-blue-500"></i>
        Campanha de Submissão
    </label>

    <div class="relative group">
        <div class="w-full pl-12 pr-4 py-4 bg-slate-100/50 border border-slate-200 rounded-2xl text-slate-500 font-semibold cursor-not-allowed flex items-center shadow-sm">
            {{ $campanha->titulo }}
        </div>

        <div class="absolute inset-y-0 left-0 flex items-center px-4 pointer-events-none text-slate-400">
            <i class="fas fa-lock text-sm"></i>
        </div>

        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-emerald-500/50">
            <i class="fas fa-check-circle"></i>
        </div>

        <input type="hidden" name="campanha_id" value="{{ $campanha->id }}">
    </div>
    
    <p class="text-[10px] text-slate-400 ml-1 italic">
        Esta submissão será vinculada automaticamente à campanha acima.
    </p>
</div>

 <div class="space-y-3">
    <label class="flex items-center text-sm font-bold text-slate-700 ml-1">
        <i class="fas fa-heading mr-2 text-blue-500"></i>
        Título do Anteprojeto
    </label>
    
    <div class="relative group">
        <input type="text" 
               name="titulo" 
               placeholder="Ex: Sistema de Gestão Académica com Laravel..."
               class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none placeholder:text-slate-400"
                      required
       minlength="3"
       maxlength="100"
       pattern="^[A-Za-zÀ-ÖØ-öø-ÿÇç0-9][A-Za-zÀ-ÖØ-öø-ÿÇç0-9 ]{2,}$"
       title="O titulo deve possuir entre 3 e 100 caracteres e conter apenas letras, números e espaços."
       >
    </div>
    <p class="text-[10px] text-slate-400 ml-1 italic">
        Use um título claro e conciso que resuma o seu tema de pesquisa.
    </p>
</div>

                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 ml-1">
                        <i class="fas fa-align-left mr-2 text-blue-500"></i>
                        Resumo do Anteprojeto
                    </label>
                    <textarea name="descricao" rows="5" 
                              placeholder="Forneça uma breve descrição dos objetivos e do problema que o seu projeto visa resolver..."
                              class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none resize-none placeholder:text-slate-400"
                              required></textarea>
                </div>

                  <div>

    <label class="block text-sm font-semibold text-slate-700 mb-2">
        Ficheiro do Anteprojeto
    </label>

    <input type="file"
           name="arquivo"
           id="arquivo"
           required
           accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
           class="w-full text-sm text-slate-500
                  file:mr-4
                  file:px-5
                  file:py-3
                  file:rounded-xl
                  file:border-0
                  file:font-semibold
                  file:bg-blue-50
                  file:text-blue-700
                  hover:file:bg-blue-100
                  border border-slate-200
                  rounded-xl
                  p-2
                  bg-slate-50/50
                  cursor-pointer
                  focus:outline-none
                  focus:ring-2
                  focus:ring-blue-500">

    <p class="mt-2 text-xs text-slate-500">
        Apenas ficheiros PDF, DOC ou DOCX.
    </p>

    <div id="nomeArquivo"
         class="hidden mt-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200">

        <span class="text-sm font-medium text-green-700"></span>

    </div>

</div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-8 md:px-12 py-8 bg-slate-50/80 border-t border-slate-100">
                <div class="flex items-center text-xs text-slate-400 italic">
                    <i class="fas fa-info-circle mr-2"></i>
                    A submissão será auditada pelo coordenador.
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <a href="{{ url()->previous() }}"
                       class="flex-1 sm:flex-none px-8 py-3.5 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all text-center">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="flex-1 sm:flex-none px-10 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:shadow-blue-300 transform hover:-translate-y-0.5 transition-all">
                        Finalizar Submissão
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
    function updateFileName() {
        const input = document.getElementById('fileInput');
        const display = document.getElementById('fileNameDisplay');
        if (input.files.length > 0) {
            display.innerText = "Selecionado: " + input.files.name;
            display.classList.add('text-blue-600');
        }
    }
</script>

@endsection