 @extends('app.private.dashboard.app.templete')

@section('title','Submeter Nova Versão')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-12">

   {{-- Link Único de Navegação Superior --}}
<div class="mb-6">
    <a href="{{ route('estudante.anteprojetos.meuAnteprojeto', $anteprojeto->campanha_id) }}" 
       class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-amber-600 transition-colors group">
        <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
        Voltar para Meu Anteprojeto
    </a>
</div>

{{-- Cabeçalho Centralizado --}}
<div class="text-center mb-12">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-50 text-amber-600 rounded-2xl mb-4 shadow-sm">
        <i class="fas fa-sync-alt text-2xl"></i>
    </div>
    
    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
        Submeter Nova Versão (V{{ $anteprojeto->versoes()->count() + 1 }})
    </h1>
    
    <p class="text-slate-500 text-base mt-2 max-w-md mx-auto">
        Você está enviando uma atualização para o tema: <br>
        <span class="font-bold text-slate-700 italic">"{{ $anteprojeto->titulo }}"</span>
    </p>
</div>
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">

        <form method="POST" action="{{ route('estudante.anteprojetos.store_versao', $anteprojeto->id) }}" enctype="multipart/form-data"
        onsubmit="let btn=this.querySelector('button[type=submit]'); btn.disabled=true; btn.innerText='A guardar...'; btn.classList.add('opacity-50', 'cursor-not-allowed');"
        >
            @csrf

            <div class="p-8 md:p-12 space-y-10">

                <div class="space-y-3 opacity-80">
                    <label class="flex items-center text-sm font-bold text-slate-700 ml-1">
                        <i class="fas fa-heading mr-2 text-slate-400"></i>
                        Título do Anteprojeto (Confirmado)
                    </label>
                    <div class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-500 font-medium">
                        {{ $anteprojeto->titulo }}
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 ml-1">
                        <i class="fas fa-comment-dots mr-2 text-blue-500"></i>
                        O que foi melhorado nesta versão?
                    </label>
                    <textarea name="descricao" rows="5" 
                              placeholder="Descreva as correções feitas com base no feedback do coordenador..."
                              class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none resize-none placeholder:text-slate-400"
                              required></textarea>
                    <p class="text-[10px] text-slate-400 ml-1 italic">Este comentário ajuda o coordenador a identificar suas melhorias rapidamente.</p>
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

            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 px-8 md:px-12 py-8 bg-slate-50/80 border-t border-slate-100">
                <a href="{{ route('estudante.acompanhamento.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-700">Voltar</a>

                <button type="submit"
                        class="w-full sm:w-auto px-10 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg transition-all">
                    Submeter V{{ $anteprojeto->versoes()->count() + 1 }}
                </button>
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