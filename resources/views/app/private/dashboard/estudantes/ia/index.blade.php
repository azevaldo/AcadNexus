 @extends('app.private.dashboard.app.templete')

@section('content')

<main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-indigo-50/40 p-6 lg:p-8">

    <div class="max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="text-center mb-10">

            <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 text-xs font-black uppercase tracking-widest mb-4">
                <i class="fas fa-brain mr-2"></i>
                Inteligência Acadêmica
            </div>

            <h1 class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight italic">
                Gerador de Temas
                <span class="text-indigo-600">Smart IA</span>
            </h1>

            <p class="mt-4 text-gray-500 max-w-2xl mx-auto text-sm leading-relaxed">
                A inteligência artificial analisa coerência acadêmica,
                qualidade da escrita, clareza científica,
                disciplinas relacionadas e viabilidade do TCC.
            </p>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- SIDEBAR --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- ALERTAS --}}
                @if(session('success'))

                    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-3xl shadow-sm">

                        <div class="flex items-start">

                            <i class="fas fa-check-circle mt-1 mr-3"></i>

                            <div>

                                <h3 class="font-black text-sm uppercase">
                                    Sucesso
                                </h3>

                                <p class="text-sm mt-1 leading-relaxed">
                                    {{ session('success') }}
                                </p>

                            </div>

                        </div>

                    </div>

                @endif

                @if(session('error'))

                    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-3xl shadow-sm">

                        <div class="flex items-start">

                            <i class="fas fa-exclamation-triangle mt-1 mr-3"></i>

                            <div>

                                <h3 class="font-black text-sm uppercase">
                                    Dados inválidos
                                </h3>

                                <p class="text-sm mt-1 leading-relaxed">
                                    {{ session('error') }}
                                </p>

                            </div>

                        </div>

                    </div>

                @endif

                {{-- INSTRUÇÕES --}}
                <div class="bg-white rounded-[2rem] border border-gray-100 shadow-lg overflow-hidden">

                    {{-- HEADER --}}
                    <button
                        type="button"
                        onclick="toggleInstructions()"
                        class="w-full flex items-center justify-between p-6 hover:bg-gray-50 transition-all duration-300"
                    >

                        <div class="flex items-center">

                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-lightbulb"></i>
                            </div>

                            <div class="ml-4 text-left">

                                <h3 class="font-black text-gray-800 uppercase text-sm tracking-wide">
                                    Como escrever corretamente
                                </h3>

                                <p class="text-xs text-gray-400 mt-1">
                                    Clique para visualizar as regras da IA
                                </p>

                            </div>

                        </div>

                        <div
                            id="instructionIcon"
                            class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 transition-all duration-300"
                        >
                            <i class="fas fa-chevron-down"></i>
                        </div>

                    </button>

                    {{-- CONTEÚDO --}}
                    <div
                        id="instructionContent"
                        class="hidden border-t border-gray-100"
                    >

                        <div class="p-6 space-y-5">

                            {{-- INTRO --}}
                            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-5">

                                <div class="flex items-start">

                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-brain"></i>
                                    </div>

                                    <div class="ml-4">

                                        <h4 class="font-black text-indigo-700 text-sm uppercase">
                                            Como a IA analisa os dados
                                        </h4>

                                        <p class="text-xs text-indigo-600 mt-2 leading-relaxed">
                                            A IA verifica coerência textual,
                                            português correto, clareza científica,
                                            relação entre disciplinas,
                                            competências e qualidade acadêmica.
                                        </p>

                                    </div>

                                </div>

                            </div>

                            {{-- ÁREA --}}
                            <div class="bg-gray-50 rounded-2xl p-5">

                                <div class="flex items-center mb-3">

                                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-flask"></i>
                                    </div>

                                    <h4 class="ml-3 font-black text-gray-800 uppercase text-xs tracking-wide">
                                        Área Científica
                                    </h4>

                                </div>

                                <p class="text-xs text-gray-500 leading-relaxed">
                                    Seja específico.
                                    Evite palavras muito genéricas.
                                </p>

                                <div class="mt-4 bg-white rounded-xl p-4 border border-gray-100">

                                    <div class="text-[10px] font-black uppercase text-green-600 mb-2">
                                        Exemplo correto
                                    </div>

                                    <p class="text-xs text-gray-700">
                                        Inteligência Artificial aplicada à Saúde
                                    </p>

                                </div>

                                <div class="mt-3 bg-white rounded-xl p-4 border border-red-100">

                                    <div class="text-[10px] font-black uppercase text-red-500 mb-2">
                                        Exemplo rejeitado
                                    </div>

                                    <p class="text-xs text-gray-700">
                                        Ciência
                                    </p>

                                </div>

                            </div>

                            {{-- DISCIPLINAS --}}
                            <div class="bg-gray-50 rounded-2xl p-5">

                                <div class="flex items-center mb-3">

                                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-book"></i>
                                    </div>

                                    <h4 class="ml-3 font-black text-gray-800 uppercase text-xs tracking-wide">
                                        Disciplinas
                                    </h4>

                                </div>

                                <p class="text-xs text-gray-500 leading-relaxed">
                                    Informe disciplinas relacionadas
                                    diretamente ao projeto.
                                </p>

                                <div class="mt-4 bg-white rounded-xl p-4 border border-gray-100">

                                    <div class="text-[10px] font-black uppercase text-green-600 mb-2">
                                        Exemplo correto
                                    </div>

                                    <p class="text-xs text-gray-700">
                                        Programação Web,
                                        Redes de Computadores,
                                        Segurança Informática
                                    </p>

                                </div>

                                <div class="mt-3 bg-white rounded-xl p-4 border border-red-100">

                                    <div class="text-[10px] font-black uppercase text-red-500 mb-2">
                                        Exemplo rejeitado
                                    </div>

                                    <p class="text-xs text-gray-700">
                                        Programação
                                    </p>

                                </div>

                            </div>

                            {{-- COMPETÊNCIAS --}}
                            <div class="bg-gray-50 rounded-2xl p-5">

                                <div class="flex items-center mb-3">

                                    <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600">
                                        <i class="fas fa-code"></i>
                                    </div>

                                    <h4 class="ml-3 font-black text-gray-800 uppercase text-xs tracking-wide">
                                        Competências Técnicas
                                    </h4>

                                </div>

                                <p class="text-xs text-gray-500 leading-relaxed">
                                    Informe ferramentas, linguagens
                                    ou habilidades técnicas reais.
                                </p>

                                <div class="mt-4 bg-white rounded-xl p-4 border border-gray-100">

                                    <div class="text-[10px] font-black uppercase text-green-600 mb-2">
                                        Exemplo correto
                                    </div>

                                    <p class="text-xs text-gray-700">
                                        Python, Laravel,
                                        Docker, AWS,
                                        Machine Learning
                                    </p>

                                </div>

                                <div class="mt-3 bg-white rounded-xl p-4 border border-red-100">

                                    <div class="text-[10px] font-black uppercase text-red-500 mb-2">
                                        Exemplo rejeitado
                                    </div>

                                    <p class="text-xs text-gray-700">
                                        Ajudar pessoas
                                    </p>

                                </div>

                            </div>

                            {{-- REGRAS --}}
                            <div class="bg-red-50 border border-red-100 rounded-2xl p-5">

                                <div class="flex items-center mb-4">

                                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600">
                                        <i class="fas fa-ban"></i>
                                    </div>

                                    <h4 class="ml-3 font-black text-red-700 uppercase text-xs tracking-wide">
                                        O que a IA rejeita
                                    </h4>

                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                                    <div class="bg-white rounded-xl p-3 text-xs text-red-600 font-semibold">
                                        • Informações incoerentes
                                    </div>

                                    <div class="bg-white rounded-xl p-3 text-xs text-red-600 font-semibold">
                                        • Palavras aleatórias
                                    </div>

                                    <div class="bg-white rounded-xl p-3 text-xs text-red-600 font-semibold">
                                        • Dados sem contexto acadêmico
                                    </div>

                                    <div class="bg-white rounded-xl p-3 text-xs text-red-600 font-semibold">
                                        • Falta de clareza científica
                                    </div>

                                    <div class="bg-white rounded-xl p-3 text-xs text-red-600 font-semibold">
                                        • Competências vagas
                                    </div>

                                    <div class="bg-white rounded-xl p-3 text-xs text-red-600 font-semibold">
                                        • Português incoerente
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- FORMULÁRIO --}}
                <form
                    action="{{ route('estudante.ia.gerar') }}"
                    method="POST"
                    class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100"
                >

                    @csrf

                    <div class="space-y-6">

                        {{-- ÁREA --}}
                        <div>

                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 block">
                                Área Científica
                            </label>

                            <input
                                type="text"
                                name="area_nome"
                                value="{{ old('area_nome') }}"
                                placeholder="Ex: Inteligência Artificial aplicada à Saúde"
                                class="w-full bg-gray-50 rounded-2xl border-none px-5 py-4 text-sm focus:ring-2 focus:ring-indigo-500"
                                required
                            >

                        </div>

                        {{-- DISCIPLINAS --}}
                        <div>

                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 block">
                                Disciplinas Relacionadas
                            </label>

                            <textarea
                                name="disciplinas"
                                rows="3"
                                placeholder="Ex: Programação Web, Redes de Computadores..."
                                class="w-full bg-gray-50 rounded-2xl border-none px-5 py-4 text-sm focus:ring-2 focus:ring-indigo-500"
                                required
                            >{{ old('disciplinas') }}</textarea>

                        </div>

                        {{-- COMPETÊNCIAS --}}
                        <div>

                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 block">
                                Competências Técnicas
                            </label>

                            <textarea
                                name="competencias"
                                rows="3"
                                placeholder="Ex: Python, Laravel, Docker..."
                                class="w-full bg-gray-50 rounded-2xl border-none px-5 py-4 text-sm focus:ring-2 focus:ring-indigo-500"
                            >{{ old('competencias') }}</textarea>

                        </div>

                        {{-- TIPO --}}
                        <div>

                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 block">
                                Tipo do Projeto
                            </label>

                            <div class="grid grid-cols-2 gap-3">

                                <label class="cursor-pointer">

                                    <input
                                        type="radio"
                                        name="tipo_projeto"
                                        value="pratico"
                                        class="hidden peer"
                                        {{ old('tipo_projeto', 'pratico') == 'pratico' ? 'checked' : '' }}
                                    >

                                    <div class="rounded-2xl border-2 border-gray-100 p-4 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all">

                                        <i class="fas fa-code text-indigo-600 mb-2"></i>

                                        <div class="text-[11px] font-black uppercase">
                                            Prático
                                        </div>

                                    </div>

                                </label>

                                <label class="cursor-pointer">

                                    <input
                                        type="radio"
                                        name="tipo_projeto"
                                        value="teorico"
                                        class="hidden peer"
                                        {{ old('tipo_projeto') == 'teorico' ? 'checked' : '' }}
                                    >

                                    <div class="rounded-2xl border-2 border-gray-100 p-4 text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all">

                                        <i class="fas fa-book-open text-indigo-600 mb-2"></i>

                                        <div class="text-[11px] font-black uppercase">
                                            Teórico
                                        </div>

                                    </div>

                                </label>

                            </div>

                        </div>

                        {{-- BOTÃO --}}
                        <button
                            type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 transition-all text-white py-5 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-indigo-200 flex items-center justify-center"
                        >

                            <i class="fas fa-robot mr-3"></i>

                            Gerar Temas Inteligentes

                        </button>

                    </div>

                </form>

            </div>

            {{-- RESULTADOS --}}
            <div class="lg:col-span-8 space-y-10">

                {{-- SUGESTÕES --}}
                <section>

                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h3 class="font-black uppercase tracking-widest text-xs text-gray-800">
                                Últimas Sugestões Geradas
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                Temas criados automaticamente pela IA
                            </p>

                        </div>

                    </div>

                    @if($sugestoes->count())

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            @foreach($sugestoes as $sug)

                                <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300">

                                    <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-600 text-[10px] font-black uppercase tracking-widest">
                                        {{ $sug->tipo_projeto }}
                                    </span>

                                    <h4 class="text-sm font-black text-gray-800 leading-relaxed mt-4 mb-3">
                                        {{ $sug->titulo }}
                                    </h4>

                                    <p class="text-xs text-gray-500 leading-relaxed mb-5">
                                        {{ $sug->descricao }}
                                    </p>

                                    <form
                                        action="{{ route('estudante.ia.favoritar', $sug->id) }}"
                                        method="POST"
                                    >

                                        @csrf

                                        <button class="text-indigo-600 text-[11px] uppercase font-black hover:text-indigo-800 transition flex items-center">

                                            <i class="far fa-star mr-2"></i>

                                            Guardar Tema

                                        </button>

                                    </form>

                                </div>

                            @endforeach

                        </div>

                        <div class="mt-8">
                            {{ $sugestoes->links() }}
                        </div>

                    @else

                        <div class="bg-white border-2 border-dashed border-gray-200 rounded-[2rem] p-16 text-center">

                            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-5">
                                <i class="fas fa-brain text-3xl text-gray-300"></i>
                            </div>

                            <h3 class="font-black text-gray-700 mb-2">
                                Nenhum tema gerado
                            </h3>

                            <p class="text-sm text-gray-400 max-w-md mx-auto">
                                Preencha os dados corretamente
                                para gerar temas inteligentes.
                            </p>

                        </div>

                    @endif

                </section>

                {{-- FAVORITOS --}}
                <section>

                    <div class="flex items-center mb-6">

                        <i class="fas fa-bookmark text-indigo-600 mr-3"></i>

                        <div>

                            <h3 class="font-black uppercase tracking-widest text-xs text-gray-800">
                                Temas Favoritos
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                Ideias guardadas para o seu anteprojeto
                            </p>

                        </div>

                    </div>

                    <div class="space-y-5">

                        @forelse($favoritos as $fav)

                            <div class="bg-indigo-900 text-white rounded-[2rem] p-7 relative overflow-hidden shadow-xl">

                                <div class="relative z-10">

                                    <div class="flex justify-between items-start">

                                        <span class="bg-white/10 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                            {{ $fav->sugestao->tipo_projeto }}
                                        </span>

                                        <form
                                            action="{{ route('estudante.ia.removerFavorito', $fav->id) }}"
                                            method="POST"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button class="text-white/50 hover:text-red-400 transition">
                                                <i class="fas fa-times"></i>
                                            </button>

                                        </form>

                                    </div>

                                    <h4 class="text-xl font-black mt-5 leading-relaxed">
                                        {{ $fav->sugestao->titulo }}
                                    </h4>

                                    <p class="text-sm text-white/70 mt-4 leading-relaxed">
                                        {{ $fav->sugestao->descricao }}
                                    </p>

                                    <button
                                        onclick="copyToClipboard('{{ $fav->sugestao->titulo }}')"
                                        class="mt-6 text-[11px] uppercase font-black flex items-center text-indigo-300 hover:text-white transition"
                                    >

                                        <i class="far fa-copy mr-2"></i>

                                        Copiar título

                                    </button>

                                </div>

                                <i class="fas fa-brain absolute -bottom-6 -right-6 text-[140px] text-white/5 rotate-[-15deg]"></i>

                            </div>

                        @empty

                            <div class="bg-white border-2 border-dashed border-gray-200 rounded-[2rem] p-14 text-center">

                                <i class="fas fa-bookmark text-4xl text-gray-300 mb-5"></i>

                                <h3 class="font-black text-gray-700 mb-2">
                                    Nenhum favorito guardado
                                </h3>

                                <p class="text-sm text-gray-400">
                                    Os temas favoritos aparecerão aqui.
                                </p>

                            </div>

                        @endforelse

                    </div>

                    <div class="mt-8">
                        {{ $favoritos->links() }}
                    </div>

                </section>

            </div>

        </div>

    </div>

</main>

<script>

    function toggleInstructions() {

        const content =
            document.getElementById('instructionContent');

        const icon =
            document.getElementById('instructionIcon');

        content.classList.toggle('hidden');

        icon.classList.toggle('rotate-180');
    }

    function copyToClipboard(text) {

        navigator.clipboard.writeText(text);

        const toast = document.createElement('div');

        toast.className =
            'fixed bottom-6 right-6 bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl z-50 text-xs font-black uppercase tracking-widest animate-bounce';

        toast.innerText = 'Título copiado com sucesso';

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

</script>

@endsection