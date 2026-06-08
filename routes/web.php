<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ItemMelhoriaController,
    AnteprojetoAvaliacaoController,
    ProfileAdminController,
    ProfileProfessorController,
    ProfileEstudanteController,
    AdminRelatorioController,
    CoordenadorRelatorioController,
    ProfessorRelatorioController,
    RelatorioEstudanteController,
    SessaoController,
    ProfileController,
    AcompanhamentoTccController,
    AreaCientificaController,
    CampanhaController,
    CoordenadorController,
    CursoController,
    DepartamentoController,
    EstudanteController,
    ProfessorController,
    AnteprojetoController,
    CampanhaEstudanteController,
    AnteprojetoAdminController,
    AvaliacaoFaseController,
    IaController,
    EstudanteAcompanhamentoController,
    EstudanteTutorController,
    SecretariaController,
    TutorController,
    SolicitacoesTutoriaController,
    NotificationCenterController,
    ProfessorConsultaController,
    EstudanteConsultaController,
    AnteprojetoGestaoController
};

/*
|--------------------------------------------------------------------------
| 1. ROTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/', [SessaoController::class, 'home'])->name('home');
Route::get('/create_estudante', [EstudanteController::class, 'create_estudante'])->name('create_estudante');
Route::resource('estudantes', EstudanteController::class);
Route::resource('professores', ProfessorController::class)->parameters(['professores' => 'professor']);

/*
|--------------------------------------------------------------------------
| 2. ROTAS AUTENTICADAS GERAIS (COMUM A TODOS LOGADOS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [SessaoController::class, 'dashboard'])->name('dashboard');

    


    //professores filtros
    // Tela principal de busca com filtros avançados
    Route::get('/professor/consulta', [ProfessorConsultaController::class, 'index'])
        ->name('professores.consultas.index');
    
    // Tela de listagem dos estudantes acompanhados por aquele professor
    Route::get('/professor/{id}/estudantes-acompanhados', [ProfessorConsultaController::class, 'estudantesAcompanhados'])
        ->name('professores.estudantesAcompanhados');


    // Centro de Notificações
    Route::prefix('dashboard/notificacoes')->name('notifications.')->group(function () {
        Route::get('/', [NotificationCenterController::class, 'index'])->name('index');
        Route::get('/ler-e-redirecionar/{id}', [NotificationCenterController::class, 'redirectAndRead'])->name('redirect');
        Route::patch('/alternar/{id}', [NotificationCenterController::class, 'toggleRead'])->name('toggle');
        Route::post('/marcar-todas-lidas', [NotificationCenterController::class, 'markAllAsRead'])->name('markAllRead');
    });

    // Perfis Isolados por Painel Administrativo / Usuários
    Route::prefix('dashboard/admin')->name('profile.admin.')->group(function () {
        Route::get('perfil', [ProfileAdminController::class, 'edit'])->name('edit');
        Route::patch('perfil/dados', [ProfileAdminController::class, 'update'])->name('update');
        Route::put('perfil/senha', [ProfileAdminController::class, 'updatePassword'])->name('password.update');
    });

    Route::middleware(['roles:professor'])->prefix('dashboard/professor')->name('profile.professor.')->group(function () {
        Route::get('perfil', [ProfileProfessorController::class, 'edit'])->name('edit');
        Route::patch('perfil/dados', [ProfileProfessorController::class, 'update'])->name('update');
        Route::put('perfil/senha', [ProfileProfessorController::class, 'updatePassword'])->name('password.update');
    });

    Route::middleware(['roles:estudante'])->prefix('dashboard/estudante')->name('profile.estudante.')->group(function () {
        Route::get('perfil', [ProfileEstudanteController::class, 'edit'])->name('edit');
        Route::patch('perfil/dados', [ProfileEstudanteController::class, 'update'])->name('update');
        Route::put('perfil/senha', [ProfileEstudanteController::class, 'updatePassword'])->name('password.update');
    });

    // Rotas Base de Tutoria (Acesso Geral Autenticado)
    Route::prefix('tutor')->name('tutor.')->group(function () {
        Route::get('estudante/{estudante}/acompanhamento/confirmar', [AcompanhamentoTccController::class, 'inicio'])->name('acompanhamento.confirm');
        Route::get('acompanhamento/{acompanhamento}', [AcompanhamentoTccController::class, 'show'])->name('acompanhamento.show');
        Route::post('estudante/{estudante}/acompanhamento/iniciar', [AcompanhamentoTccController::class, 'storeBasic'])->name('acompanhamento.storeBasic');
        Route::post('fase/{fase}/avaliar', [AcompanhamentoTccController::class, 'avaliarFase'])->name('fase.avaliar');
    });
});

/*
|--------------------------------------------------------------------------
| 3. ECOSSISTEMA DO ESTUDANTE (AUTH + VERIFIED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('campanhas', CampanhaController::class);

    Route::middleware(['roles:estudante'])->group(function () {
        // Solicitações de Tutoria
        Route::get('/estudante/solicitacoes', [SolicitacoesTutoriaController::class, 'indexEstudante'])->name('solicitacoes.estudante.index');
        Route::get('/estudante/solicitacoes/nova', [SolicitacoesTutoriaController::class, 'create'])->name('solicitacoes.create');
        Route::post('/estudante/solicitacoes/{professor_id}', [SolicitacoesTutoriaController::class, 'store'])->name('solicitacoes.store');
Route::post('/ia/gerar-por-narrativa', [IaController::class, 'gerarPorNarrativa'])->name('estudante.ia.gerar_narrativa');
        // Assistente de Inteligência Artificial
        Route::prefix('estudante/ia')->name('estudante.ia.')->group(function () {
            Route::get('/sugestoes', [IaController::class, 'index'])->name('index');
            Route::post('/gerar', [IaController::class, 'gerar'])->name('gerar');
            Route::post('/favoritar/{id}', [IaController::class, 'favoritar'])->name('favoritar');
            Route::delete('/favoritos/{id}', [IaController::class, 'removerFavorito'])->name('removerFavorito');
        });

        // Relatórios do Estudante
        Route::prefix('estudante/relatorios')->name('estudante.relatorios.')->group(function () {
            Route::get('/', [RelatorioEstudanteController::class, 'index'])->name('index');
            Route::get('/exportar-pdf', [RelatorioEstudanteController::class, 'exportarPdf'])->name('pdf');
            Route::get('/temas-por-area', [RelatorioEstudanteController::class, 'temasPorArea'])->name('areas');
        });

        // Gestão de Anteprojetos e Versões
        Route::prefix('estudante')->name('estudante.')->group(function () {
            Route::patch('anteprojetos/{id}/update-titulo', [AnteprojetoController::class, 'updateTitulo'])->name('anteprojetos.update_titulo');
            Route::get('/anteprojetos/{id}/nova-versao', [AnteprojetoController::class, 'novaVersao'])->name('anteprojetos.nova_versao');
            Route::post('/anteprojetos/{id}/store-versao', [AnteprojetoController::class, 'storeVersao'])->name('anteprojetos.store_versao');
            Route::get('/campanhas', [CampanhaEstudanteController::class, 'index'])->name('campanhaestudante.index');
            Route::get('/campanha/{id}', [CampanhaEstudanteController::class, 'show'])->name('campanhaestudante.show');
            Route::get('/anteprojetos/meu/{id}', [AnteprojetoController::class, 'meuAnteprojeto'])->name('anteprojetos.meuAnteprojeto');
            Route::get('/anteprojetos/novo/{id}', [AnteprojetoController::class, 'create'])->name('anteprojetos.create');
            Route::post('/anteprojetos/salvar', [AnteprojetoController::class, 'store'])->name('anteprojetos.store');
            Route::get('/anteprojeto/{id}/visualizar', [AnteprojetoController::class, 'show'])->name('anteprojetos.show');
            Route::delete('/anteprojetos/versao/{id}', [AnteprojetoController::class, 'destroy_versao'])->name('anteprojetos.versao.destroy');

            // Acompanhamentos por parte do Estudante
            Route::get('/acompanhamentos', [EstudanteAcompanhamentoController::class, 'index'])->name('acompanhamento.index');
            Route::get('/acompanhamento/{acompanhamento}', [EstudanteAcompanhamentoController::class, 'show'])->name('acompanhamento.show');
            Route::post('fases/{faseId}/submeter', [EstudanteAcompanhamentoController::class, 'storeSubmissao'])->name('acompanhamentos.storeSubmissao');
            Route::delete('submissao/{id}/excluir', [EstudanteAcompanhamentoController::class, 'destroy'])->name('acompanhamentos.destroySubmissao');
        });
    });
});

/*
|--------------------------------------------------------------------------
| 4. ECOSSISTEMA DO PROFESSOR / TUTOR / ORIENTADOR
|--------------------------------------------------------------------------
*/
Route::middleware(['roles:estudante,professor'])->prefix('tutor.')->name('tutor.')->group(function () {
    Route::get('acompanhamento/{acompanhamento}/configurar-fases', [AcompanhamentoTccController::class, 'createFases'])->name('acompanhamento.createFases');
    Route::post('acompanhamento/{acompanhamento}/salvar-fases', [AcompanhamentoTccController::class, 'storeFases'])->name('acompanhamento.storeFases');
    Route::post('/tutor/submissao/{id}/avaliar', [AcompanhamentoTccController::class, 'avaliarFase'])->name('fase.avaliar');
    Route::delete('/tutor/avaliacao/{avaliacao}', [AcompanhamentoTccController::class, 'destroyAvaliacao'])->name('avaliacao.destroy');
    Route::get('acompanhamento/{acompanhamento}/editar', [AcompanhamentoTccController::class, 'edit'])->name('acompanhamento.edit');
    Route::put('acompanhamento/{acompanhamento}/atualizar', [AcompanhamentoTccController::class, 'update'])->name('acompanhamento.update');
});

Route::middleware(['auth'])->group(function () {
    // Relatórios Privados do Professor
    Route::prefix('app/private')->group(function () {
        Route::middleware(['roles:professor'])->prefix('professor')->name('professor.')->group(function () {
            Route::get('/relatorios', [ProfessorRelatorioController::class, 'index'])->name('relatorios.index');
        });
    });

    Route::middleware(['roles:professor'])->group(function () {
        Route::get('/minhas/campanhas', [ProfessorController::class, 'minhasCamapanhas'])->name('professores.campanhas');
Route::get('/professor/meus-departamentos', [ProfessorController::class, 'meusDepartamentos'])
        ->name('professor.departamentos');
        // Painel Interno de Orientação / Tutoria
        Route::prefix('orientacao')->name('tutor.')->group(function () {
            Route::get('/meus-estudantes', [TutorController::class, 'index'])->name('estudantes.index');
            Route::get('/acompanhamento/{acompanhamento}', [AcompanhamentoTccController::class, 'show'])->name('acompanhamento.show');
            Route::post('/fase/{fase}/avaliar', [AcompanhamentoTccController::class, 'avaliarFase'])->name('fase.avaliar');
            Route::post('/fase/{fase}/comentario-tecnico', [AvaliacaoFaseController::class, 'store'])->name('fase.comentario');
            Route::get('/estudante/{estudante}/iniciar-tcc', [AcompanhamentoTccController::class, 'create'])->name('acompanhamento.create');
            Route::post('/estudante/{estudante}/salvar-tcc', [AcompanhamentoTccController::class, 'store'])->name('acompanhamento.store');
        });
    });
});

/*
|--------------------------------------------------------------------------
| 5. ROTAS DE ACESSO MULTI-CORPO (PROFESSOR, ADMIN, COORDENADOR)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'roles:professor,admin,coordenador'])->group(function () {
    Route::patch('professores/{id}/toggle-acesso', [ProfessorController::class, 'toggleAcesso'])->name('professores.toggleAcesso');
    Route::patch('/estudantes/{id}/toggle-acesso', [EstudanteController::class, 'toggleAcesso'])->name('estudantes.toggleAcesso');
    Route::get('/campanhas/{campanha_id}/anteprojetos', [AnteprojetoAdminController::class, 'index'])->name('admin.campanhas.anteprojetos');
    Route::get('/anteprojeto/{id}/visualizar', [AnteprojetoAdminController::class, 'show'])->name('anteprojetosAdmin.show');
    Route::get('/campanhas/estudantes/{campanha}', [CampanhaController::class, 'estudantes'])->name('campanhas.estudantes');
    Route::get('/{id}/historico', [AnteprojetoAdminController::class, 'historicoAnteprojeto'])->name('anteprojetosAdmin.historico');

    //cursos
     Route::get('/cursos/{curso}/estudantes', [CursoController::class, 'estudantePorCurso'])->name('cursos.estudantes');
       Route::get('/cursos/{curso}/campanhas', [CampanhaController::class, 'indexPorCurso'])->name('cursos.campanhas');  
     Route::get('/departamento/cursos/{departamento}/{privado?}', [DepartamentoController::class, 'cursos'])->name('departamento.cursos');

// Rota limpa para abrir a tela direto do menu ou com filtros via GET
    Route::get('/estudante/consulta', [EstudanteConsultaController::class, 'estudantePorCurso'])
    ->name('estudantes.consultas');
 

// Rota 100% limpa, sem parâmetros na URL
Route::get('/anteprojetos/gestao', [AnteprojetoGestaoController::class, 'index'])
    ->name('anteprojetosAdmin.index');
    
});

Route::middleware(['auth', 'roles:professor|coordenador'])->group(function () {
    Route::get('/professor/solicitacoes', [SolicitacoesTutoriaController::class, 'indexTutor'])->name('solicitacoes.tutor.index');
    Route::post('/professor/solicitacoes/{id}/responder', [SolicitacoesTutoriaController::class, 'responder'])->name('solicitacoes.responder');
});

/*
|--------------------------------------------------------------------------
| 6. CORPO ADMINISTRATIVO: COORDENADOR E SECRETARIA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'roles:coordenador'])->group(function () {
    Route::prefix('app/private/coordenador')->name('coordenador.')->group(function () {
        Route::get('/relatorio-geral', [CoordenadorRelatorioController::class, 'index'])->name('relatorios.index');
    });
});

Route::middleware(['auth', 'roles:admin'])->group(function () {
    Route::prefix('admin/relatorios')->group(function () {
        Route::get('/geral', [AdminRelatorioController::class, 'index'])->name('admin.relatorios.geral');
    });
    Route::get('/admin/usuarios/recentes', [AdminRelatorioController::class, 'usuarios'])
    ->name('admin.usuarios.recentes');
});

Route::middleware(['auth', 'roles:coordenador|admin'])->group(function () {
    // Remoção de Pareceres Técnicos / Decisões
    Route::delete('/admin/decisoes/{decisao}', [AnteprojetoAvaliacaoController::class, 'destroy'])->name('anteprojetosAdmin.destroyAvaliacao');

    // Painel Administrativo de Controle Global
    Route::prefix('admin')->group(function () {
        Route::post('/{id}/avaliar', [AnteprojetoAdminController::class, 'storeAvaliacao'])->name('anteprojetosAdmin.storeAvaliacao');
        Route::post('/anteprojeto/{id}/decisao', [AnteprojetoAdminController::class, 'storeDecisao'])->name('anteprojetos.decisao');
        Route::put('/campanhas/{campanha}/prazos', [CampanhaController::class, 'updatePrazos'])->name('campanhas.updatePrazos');
        Route::post('/campanhas/{campanha}/estudantes', [CampanhaController::class, 'addEstudante'])->name('campanhas.estudantes.store');
        Route::delete('/campanhas/{campanha}/estudantes/{estudante}', [CampanhaController::class, 'desaEstudante'])->name('campanhas.estudantes.destroy');
        Route::post('/estudantes/{estudante}/tutores', [EstudanteTutorController::class, 'vincularTutores'])->name('estudantes.tutores.vincular');
        Route::delete('/estudantes/{estudante}/tutores/desvincular', [EstudanteTutorController::class, 'desvincular'])->name('estudantes.tutores.desvincular');
        
        // Departamentos e Cursos
        Route::get('/departamentos/{departamento}/professores', [DepartamentoController::class, 'professorPorDepartamento'])->name('departamentos.professores');
      
        Route::get('/cursos/{curso}/campanhas/create', [CampanhaController::class, 'cursoCampanhaCreate'])->name('cursos.campanhas.create');
       

        // Recursos Crud Estruturais
        Route::resource('departamentos', DepartamentoController::class);
        Route::resource('cursos', CursoController::class);
        Route::resource('itensMelhoria', ItemMelhoriaController::class);

        // Recursos com Proteção Adicional Integrada (Secretaria / Admin)
        Route::resource('areasCientificas', AreaCientificaController::class)->middleware(['roles:secretaria|admin']);
        Route::resource('coordenadores', CoordenadorController::class)->parameters(['coordenadores' => 'coordenador'])->middleware(['roles:secretaria|admin']);
    });
});

require __DIR__.'/auth.php';