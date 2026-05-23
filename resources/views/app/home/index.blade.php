@extends('app.home.app.templete')

@section('content')
 <section class="pt-32 pb-20 hero-pattern">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8">
                <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-widest">Digitalização Académica</span>
                <h1 class="text-5xl lg:text-6xl font-extrabold text-blue-950 leading-tight">
                    Excelência na Gestão de <span class="text-blue-600">Anteprojetos</span> e TCC.
                </h1>
                <p class="text-lg text-gray-600 leading-relaxed">
                    O <strong>IP ACADÉMICO</strong> é o ecossistema digital do nosso Instituto Superior, desenhado para organizar, rastrear e elevar a qualidade da investigação científica de estudantes e professores.
                </p>
                
            </div>
            <div class="relative">
                <div class="bg-blue-100 rounded-3xl p-4 transform rotate-2">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Estudantes" class="rounded-2xl shadow-2xl transform -rotate-2">
                </div>
            </div>
        </div>
    </section>

    <section id="sobre" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-blue-950 mb-4">Central de Controlo Académico</h2>
                <p class="text-gray-500 italic">Uma plataforma que elimina a burocracia e foca no que importa: o conhecimento.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 border border-gray-100 rounded-3xl hover:border-blue-200 hover:bg-blue-50/30 transition group">
                    <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-file-signature text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-950 mb-3">Submissão Digital</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Campanhas de anteprojeto organizadas por curso, com prazos claros e upload de documentos seguro.</p>
                </div>

                <div class="p-8 border border-gray-100 rounded-3xl hover:border-blue-200 hover:bg-blue-50/30 transition group">
                    <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-user-tie text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-950 mb-3">Tutoria Inteligente</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Recomendação de tutores baseada em áreas científicas e disponibilidade real do corpo docente.</p>
                </div>

                <div class="p-8 border border-gray-100 rounded-3xl hover:border-blue-200 hover:bg-blue-50/30 transition group">
                    <div class="w-14 h-14 bg-blue-100 text-blue-700 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-check-double text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-blue-950 mb-3">Acompanhamento</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Fluxo estruturado por fases (Checklist), garantindo que cada etapa do TCC seja validada pelo tutor.</p>
                </div>
            </div>
        </div>
    </section>

@endsection