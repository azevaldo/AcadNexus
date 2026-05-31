 <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemMelhoriaController;
use App\Http\Controllers\{AnteprojetoAvaliacaoController,ProfileAdminController,ProfileProfessorController,ProfileEstudanteController,AdminRelatorioController,CoordenadorRelatorioController,ProfessorRelatorioController,RelatorioEstudanteController,
    SessaoController, ProfileController, AcompanhamentoTccController,
    AreaCientificaController, CampanhaController, CoordenadorController,
    CursoController, DepartamentoController, EstudanteController,
    ProfessorController, AnteprojetoController, CampanhaEstudanteController,
    AnteprojetoAdminController, AvaliacaoFaseController,IaController,
    EstudanteAcompanhamentoController, EstudanteTutorController, SecretariaController, TutorController,SolicitacoesTutoriaController
    };
use App\Http\Controllers\NotificationCenterController;


Route::middleware(['auth', 'roles:coordenador|admin'])->group(function () {
    Route::delete('/admin/decisoes/{decisao}', [AnteprojetoAvaliacaoController::class, 'destroy'])
        ->name('anteprojetosAdmin.destroyAvaliacao');
});
 Route::resource('professores', ProfessorController::class)->parameters(['professores' => 'professor']);
        
Route::middleware(['auth'])->prefix('dashboard/notificacoes')->name('notifications.')->group(function () {
    Route::get('/', [NotificationCenterController::class, 'index'])->name('index');
    Route::get('/ler-e-redirecionar/{id}', [NotificationCenterController::class, 'redirectAndRead'])->name('redirect');
    Route::patch('/alternar/{id}', [NotificationCenterController::class, 'toggleRead'])->name('toggle');
    Route::post('/marcar-todas-lidas', [NotificationCenterController::class, 'markAllAsRead'])->name('markAllRead');
});

 Route::middleware(['roles:estudante,professor'])->prefix('tutor.')->name('tutor.')->group(function () {
    // 3. View para Configurar as Fases (Passo 2 - Opcional/Posterior)
    Route::get('acompanhamento/{acompanhamento}/configurar-fases', [AcompanhamentoTccController::class, 'createFases'])
        ->name('acompanhamento.createFases');

    // 4. Salvar as Fases e o Mês de Referência
    Route::post('acompanhamento/{acompanhamento}/salvar-fases', [AcompanhamentoTccController::class, 'storeFases'])
        ->name('acompanhamento.storeFases');

    // 5. Visualização e Outros
    //Route::get('acompanhamento/{acompanhamento}', [AcompanhamentoTccController::class, 'show'])
      //  ->name('acompanhamento.show');
        Route::post('/tutor/submissao/{id}/avaliar', [AcompanhamentoTccController::class, 'avaliarFase'])->name('fase.avaliar');
Route::delete('/tutor/avaliacao/{avaliacao}', [AcompanhamentoTccController::class, 'destroyAvaliacao'])->name('avaliacao.destroy');
        
    Route::get('acompanhamento/{acompanhamento}/editar', [AcompanhamentoTccController::class, 'edit'])
        ->name('acompanhamento.edit');
        
    Route::put('acompanhamento/{acompanhamento}/atualizar', [AcompanhamentoTccController::class, 'update'])
        ->name('acompanhamento.update');

Route::get('/acompanhamento/{acompanhamento}/editar', [AcompanhamentoTccController::class, 'edit'])->name('acompanhamento.edit');
        Route::put('/acompanhamento/{acompanhamento}/atualizar', [AcompanhamentoTccController::class, 'update'])->name('acompanhamento.update');

 });
Route::middleware(['auth'])->prefix('dashboard/admin')->name('profile.admin.')->group(function () {
    Route::get('perfil', [ProfileAdminController::class, 'edit'])->name('edit');
    Route::patch('perfil/dados', [ProfileAdminController::class, 'update'])->name('update');
    Route::put('perfil/senha', [ProfileAdminController::class, 'updatePassword'])->name('password.update');
});

Route::middleware(['auth','roles:professor'])->prefix('dashboard/professor')->name('profile.professor.')->group(function () {
    Route::get('perfil', [ProfileProfessorController::class, 'edit'])->name('edit');
    Route::patch('perfil/dados', [ProfileProfessorController::class, 'update'])->name('update');
    Route::put('perfil/senha', [ProfileProfessorController::class, 'updatePassword'])->name('password.update');
});
Route::middleware(['auth','roles:estudante'])->prefix('dashboard/estudante')->name('profile.estudante.')->group(function () {
    Route::get('perfil', [ProfileEstudanteController::class, 'edit'])->name('edit');
    Route::patch('perfil/dados', [ProfileEstudanteController::class, 'update'])->name('update');
    Route::put('perfil/senha', [ProfileEstudanteController::class, 'updatePassword'])->name('password.update');
});
Route::prefix('tutor')->name('tutor.')->middleware(['auth'])->group(function () {
    
    // --- Acompanhamento TCC ---

    // 1. View de Confirmação (Passo 1 - Antes de criar)
    Route::get('estudante/{estudante}/acompanhamento/confirmar', [AcompanhamentoTccController::class, 'inicio'])
        ->name('acompanhamento.confirm');

          // 5. Visualização e Outros
    Route::get('acompanhamento/{acompanhamento}', [AcompanhamentoTccController::class, 'show'])
        ->name('acompanhamento.show');

    // 2. Salvar Vínculo Inicial (Cria o registro sem fases)
    Route::post('estudante/{estudante}/acompanhamento/iniciar', [AcompanhamentoTccController::class, 'storeBasic'])
        ->name('acompanhamento.storeBasic');

 
    // 5. Visualização e Outros
    Route::get('acompanhamento/{acompanhamento}', [AcompanhamentoTccController::class, 'show'])
        ->name('acompanhamento.show');
        
  
    Route::post('fase/{fase}/avaliar', [AcompanhamentoTccController::class, 'avaliarFase'])
        ->name('fase.avaliar');
});


Route::middleware(['auth', 'roles:admin'])->prefix('admin/relatorios')->group(function () {
    Route::get('/geral', [AdminRelatorioController::class, 'index'])->name('admin.relatorios.geral');
});
    Route::middleware(['auth'])->prefix('app/private')->group(function () {

    // Grupo específico para funcionalidades do Professor
    Route::prefix('professor')->name('professor.')->group(function () {
        
        // Rota principal do Dashboard/Relatórios
        // URL: /app/private/professor/relatorios
        // Nome da rota: professor.relatorios.index
        Route::get('/relatorios', [ProfessorRelatorioController::class, 'index'])
            ->name('relatorios.index');

        // Podes adicionar outras consultas futuras aqui, como:
        // Route::get('/orientandos/{id}', [ProfessorRelatorioController::class, 'showOrientando'])->name('orientandos.show');
        // Route::get('/exportar-pauta', [ProfessorRelatorioController::class, 'exportar'])->name('relatorios.exportar');
    });

});
Route::middleware(['auth', 'roles:coordenador'])->prefix('app/private/coordenador')->name('coordenador.')->group(function () {
    Route::get('/relatorio-geral', [CoordenadorRelatorioController::class, 'index'])->name('relatorios.index');
});
Route::prefix('estudante/ia')->name('estudante.ia.')->group(function () {
    Route::get('/sugestoes', [IaController::class, 'index'])->name('index');
    Route::post('/gerar', [IaController::class, 'gerar'])->name('gerar');
    Route::post('/favoritar/{id}', [IaController::class, 'favoritar'])->name('favoritar');
    Route::delete('/favoritos/{id}', [IaController::class, 'removerFavorito'])->name('removerFavorito');
});
Route::prefix('estudante/relatorios')->name('estudante.relatorios.')->group(function () {
    Route::get('/', [RelatorioEstudanteController::class, 'index'])->name('index');
    Route::get('/exportar-pdf', [RelatorioEstudanteController::class, 'exportarPdf'])->name('pdf');
    Route::get('/temas-por-area', [RelatorioEstudanteController::class, 'temasPorArea'])->name('areas');
});
// Rotas para o Estudante
Route::middleware(['auth', 'roles:estudante'])->group(function () {
 

    // --- ROTAS DO ESTUDANTE ---
    // Histórico de pedidos
    Route::get('/estudante/solicitacoes', [SolicitacoesTutoriaController::class, 'indexEstudante'])
        ->name('solicitacoes.estudante.index');
    
    // Tela com lista de professores para escolher
    Route::get('/estudante/solicitacoes/nova', [SolicitacoesTutoriaController::class, 'create'])
        ->name('solicitacoes.create');
    
    // Ação de enviar o formulário
    Route::post('/estudante/solicitacoes/{professor_id}', [SolicitacoesTutoriaController::class, 'store'])
        ->name('solicitacoes.store');

});

// Rotas para o Professor/Tutor
Route::middleware(['auth', 'roles:professor|coordenador'])->group(function () {
 // --- ROTAS DO PROFESSOR ---
    // Painel para ver quem solicitou a ele
    Route::get('/professor/solicitacoes', [SolicitacoesTutoriaController::class, 'indexTutor'])
        ->name('solicitacoes.tutor.index');
    
    // Ação de aceitar ou recusar
    Route::post('/professor/solicitacoes/{id}/responder', [SolicitacoesTutoriaController::class, 'responder'])
        ->name('solicitacoes.responder');   

});
// --- ROTAS PÚBLICAS ---
Route::get('/', [SessaoController::class, 'home'])->name('home');
Route::get('/create_estudante', [EstudanteController::class, 'create_estudante'])
->name('create_estudante');
Route::resource('estudantes', EstudanteController::class);

// --- ROTAS AUTENTICADAS (COMUM A TODOS) ---
Route::middleware(['auth', 'verified'])->group(function () {
     Route::resource('campanhas', CampanhaController::class);
    Route::get('/dashboard', [SessaoController::class, 'dashboard'])->name('dashboard');

   
    // --- GRUPO: ESTUDANTE ---
            Route::middleware(['roles:estudante'])->prefix('estudante')->name('estudante.')->group(function () {
       Route::patch('anteprojetos/{id}/update-titulo', [AnteprojetoController::class, 'updateTitulo'])
         ->name('anteprojetos.update_titulo');
            Route::get('/anteprojetos/{id}/nova-versao', [AnteprojetoController::class, 'novaVersao'])->name('anteprojetos.nova_versao');
        Route::post('/anteprojetos/{id}/store-versao', [AnteprojetoController::class, 'storeVersao'])->name('anteprojetos.store_versao');

            Route::get('/campanhas', [CampanhaEstudanteController::class, 'index'])->name('campanhaestudante.index');
        Route::get('/campanha/{id}', [CampanhaEstudanteController::class, 'show'])->name('campanhaestudante.show');
        Route::get('/anteprojetos/meu/{id}', [AnteprojetoController::class, 'meuAnteprojeto'])->name('anteprojetos.meuAnteprojeto');
        Route::get('/anteprojetos/novo/{id}', [AnteprojetoController::class, 'create'])->name('anteprojetos.create');
        Route::post('/anteprojetos/salvar', [AnteprojetoController::class, 'store'])->name('anteprojetos.store');
        Route::get('/anteprojeto/{id}/visualizar', [AnteprojetoController::class, 'show'])->name('anteprojetos.show');
        Route::delete('/anteprojetos/versao/{id}', [AnteprojetoController::class, 'destroy_versao'])
     ->name('anteprojetos.versao.destroy');
        // Acompanhamento de TCC
        Route::get('/acompanhamentos', [EstudanteAcompanhamentoController::class, 'index'])->name('acompanhamento.index');
        Route::get('/acompanhamento/{acompanhamento}', [EstudanteAcompanhamentoController::class, 'show'])->name('acompanhamento.show');
       Route::post('estudante/fases/{faseId}/submeter', [EstudanteAcompanhamentoController::class, 'storeSubmissao'])->name('acompanhamentos.storeSubmissao');
Route::delete('estudante/submissao/{id}/excluir', [EstudanteAcompanhamentoController::class, 'destroy'])->name('acompanhamentos.destroySubmissao');
        // Route::post('/fase/{fase}/submeter', [EstudanteAcompanhamentoController::class, 'storeSubmissao'])->name('submissao.store');
       // Route::delete('/submissao/{id}', [EstudanteAcompanhamentoController::class, 'destroy'])->name('submissao.destroy');
    });
//professor,secretaria,admin,coordenador
    Route::middleware(['roles:professor,admin,coordenador'])->group(function () {
        Route::patch('professores/{id}/toggle-acesso', [ProfessorController::class, 'toggleAcesso'])->name('professores.toggleAcesso');
        Route::patch('/estudantes/{id}/toggle-acesso', [EstudanteController::class, 'toggleAcesso'])->name('estudantes.toggleAcesso');
        // Anteprojetos (Administração)
        Route::get('/campanhas/{campanha_id}/anteprojetos', [AnteprojetoAdminController::class, 'index'])->name('admin.campanhas.anteprojetos');
        Route::get('/anteprojeto/{id}/visualizar', [AnteprojetoAdminController::class, 'show'])->name('anteprojetosAdmin.show');
   
           // Campanhas
        Route::get('/campanhas/estudantes/{campanha}', [CampanhaController::class, 'estudantes'])->name('campanhas.estudantes');
        
        // Rota para o Histórico Completo de um Anteprojeto específico
    Route::get('/{id}/historico', [AnteprojetoAdminController::class, 'historicoAnteprojeto'])
        ->name('anteprojetosAdmin.historico');

        
        });
    Route::middleware(['roles:professor'])->group(function () {

Route::get('/minhas/campanhas', [ProfessorController::class, 'minhasCamapanhas'])->name('professores.campanhas');
    }); 
    // --- GRUPO: PROFESSOR / ORIENTADOR ---
    Route::middleware(['roles:professor'])->prefix('orientacao')->name('tutor.')->group(function () {
    
    
    Route::get('/meus-estudantes', [TutorController::class, 'index'])->name('estudantes.index');
        
        // Gestão de Acompanhamentos
        Route::get('/acompanhamento/{acompanhamento}', [AcompanhamentoTccController::class, 'show'])->name('acompanhamento.show');
        
        
        // Avaliações de Fases
        Route::post('/fase/{fase}/avaliar', [AcompanhamentoTccController::class, 'avaliarFase'])->name('fase.avaliar');
        Route::post('/fase/{fase}/comentario-tecnico', [AvaliacaoFaseController::class, 'store'])->name('fase.comentario');
        
        // Iniciar novo TCC para estudante aprovado
        Route::get('/estudante/{estudante}/iniciar-tcc', [AcompanhamentoTccController::class, 'create'])->name('acompanhamento.create');
        Route::post('/estudante/{estudante}/salvar-tcc', [AcompanhamentoTccController::class, 'store'])->name('acompanhamento.store');
    });

    // --- GRUPO: COORDENADOR & SECRETARIA (GESTÃO ADM) ---
    Route::middleware(['roles:coordenador|admin'])->prefix('admin')->group(function () {
        


    // Rota para a tela de Avaliação de uma versão específica
   // Route::get('/versao/{versao_id}/avaliar', [AnteprojetoAdminController::class, 'showAvaliacao'])
     //   ->name('anteprojetosAdmin.show');

    // Rota POST para processar a avaliação (o formulário envia para cá)
    Route::post('/{id}/avaliar', [AnteprojetoAdminController::class, 'storeAvaliacao'])
        ->name('anteprojetosAdmin.storeAvaliacao');


        // Anteprojetos (Administração)

        Route::post('/anteprojeto/{id}/decisao', [AnteprojetoAdminController::class, 'storeDecisao'])->name('anteprojetos.decisao');

        // Campanhas
 Route::put('/campanhas/{campanha}/prazos', [CampanhaController::class, 'updatePrazos'])->name('campanhas.updatePrazos');
        Route::post('/campanhas/{campanha}/estudantes', [CampanhaController::class, 'addEstudante'])->name('campanhas.estudantes.store');
        Route::delete('/campanhas/{campanha}/estudantes/{estudante}', [CampanhaController::class, 'desaEstudante'])->name('campanhas.estudantes.destroy');

        // Vinculação Estudante <-> Tutor
        Route::post('/estudantes/{estudante}/tutores', [EstudanteTutorController::class, 'vincularTutores'])->name('estudantes.tutores.vincular');
        Route::delete('/estudantes/{estudante}/tutores/desvincular', [EstudanteTutorController::class, 'desvincular'])->name('estudantes.tutores.desvincular');
       
        //departamentos
        Route::get('/departamentos/{departamento}/professores', [DepartamentoController::class, 'professorPorDepartamento'])
    ->name('departamentos.professores');
        //cursos
        Route::get('/cursos/{curso}/campanhas', [CampanhaController::class, 'indexPorCurso'])->name('cursos.campanhas');
        Route::get('/cursos/{curso}/campanhas/create', [CampanhaController::class, 'cursoCampanhaCreate'])->name('cursos.campanhas.create');
        Route::get('/cursos/{curso}/estudantes', [CursoController::class, 'estudantePorCurso'])
    ->name('cursos.estudantes');
  

        // Recursos Básicos
        Route::resource('departamentos', DepartamentoController::class);
        Route::resource('cursos', CursoController::class);
        Route::get('/departamento/cursos/{departamento}',[DepartamentoController::class,'cursos'])->name('departamento.cursos');
       
        Route::resource('areasCientificas', AreaCientificaController::class)-> middleware(['roles:secretaria|admin']);
        Route::resource('itensMelhoria', ItemMelhoriaController::class);
        Route::resource('coordenadores', CoordenadorController::class)->parameters(['coordenadores' => 'coordenador'])-> middleware(['roles:secretaria|admin']);
    
    //Route::resource('secretarias', SecretariaController::class)-> middleware(['roles:admin']);
        });

});

require __DIR__.'/auth.php';