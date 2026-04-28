 @extends('app.private.dashboard.app.templete')

@section('title','Submeter Nova Versão')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-12">

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

        <form method="POST" action="{{ route('estudante.anteprojetos.store_versao', $anteprojeto->id) }}" enctype="multipart/form-data">
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

                <div class="space-y-3">
                    <label class="flex items-center text-sm font-bold text-slate-700 ml-1">
                        <i class="fas fa-file-pdf mr-2 text-blue-500"></i>
                        Novo Documento Atualizado
                    </label>
                    
                    <div class="group relative">
                        <input type="file" name="arquivo" id="fileInput" class="hidden" accept=".pdf,.doc,.docx" required onchange="updateFileName()">
                        <label for="fileInput" 
                               class="flex flex-col items-center justify-center w-full min-h-[180px] border-2 border-dashed border-slate-200 rounded-3xl cursor-pointer bg-slate-50/50 hover:bg-amber-50/50 hover:border-amber-400 transition-all duration-300">
                            
                            <div class="flex flex-col items-center justify-center py-6 text-center px-4">
                                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100 mb-4 group-hover:scale-110 transition-transform">
                                    <i class="far fa-file-alt text-xl text-slate-400 group-hover:text-amber-600"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600" id="fileNameDisplay">
                                    Clique para carregar a nova versão
                                </p>
                            </div>
                        </label>
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