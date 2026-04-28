 <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    SessaoController, ProfileController, AcompanhamentoTccController,
    AreaCientificaController, CampanhaController, CoordenadorController,
    CursoController, DepartamentoController, EstudanteController,
    ProfessorController, AnteprojetoController, CampanhaEstudanteController,
    AnteprojetoAdminController, AvaliacaoFaseController,
    EstudanteAcompanhamentoController, EstudanteTutorController, SecretariaController, TutorController,SolicitacoesTutoriaController
    };

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

// --- ROTAS AUTENTICADAS (COMUM A TODOS) ---
Route::middleware(['auth', 'verified'])->group(function () {
     Route::resource('campanhas', CampanhaController::class);
    Route::get('/dashboard', [SessaoController::class, 'dashboard'])->name('dashboard');

    // Perfil do Usuário
        Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

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
        Route::post('/fase/{fase}/submeter', [EstudanteAcompanhamentoController::class, 'storeSubmissao'])->name('submissao.store');
        Route::delete('/submissao/{id}', [EstudanteAcompanhamentoController::class, 'destroy'])->name('submissao.destroy');
    });
//professor,secretaria,admin,coordenador
    Route::middleware(['roles:professor,secretaria,admin,coordenador'])->group(function () {
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
        Route::get('/acompanhamento/{acompanhamento}/editar', [AcompanhamentoTccController::class, 'edit'])->name('acompanhamento.edit');
        Route::put('/acompanhamento/{acompanhamento}/atualizar', [AcompanhamentoTccController::class, 'update'])->name('acompanhamento.update');
        
        // Avaliações de Fases
        Route::post('/fase/{fase}/avaliar', [AcompanhamentoTccController::class, 'avaliarFase'])->name('fase.avaliar');
        Route::post('/fase/{fase}/comentario-tecnico', [AvaliacaoFaseController::class, 'store'])->name('fase.comentario');
        
        // Iniciar novo TCC para estudante aprovado
        Route::get('/estudante/{estudante}/iniciar-tcc', [AcompanhamentoTccController::class, 'create'])->name('acompanhamento.create');
        Route::post('/estudante/{estudante}/salvar-tcc', [AcompanhamentoTccController::class, 'store'])->name('acompanhamento.store');
    });

    // --- GRUPO: COORDENADOR & SECRETARIA (GESTÃO ADM) ---
    Route::middleware(['roles:coordenador|secretaria|admin'])->prefix('admin')->group(function () {
        


    // Rota para a tela de Avaliação de uma versão específica
   // Route::get('/versao/{versao_id}/avaliar', [AnteprojetoAdminController::class, 'showAvaliacao'])
     //   ->name('anteprojetosAdmin.show');

    // Rota POST para processar a avaliação (o formulário envia para cá)
    Route::post('/{id}/avaliar', [AnteprojetoAdminController::class, 'storeAvaliacao'])
        ->name('anteprojetosAdmin.storeAvaliacao');


        // Anteprojetos (Administração)

        Route::post('/anteprojeto/{id}/decisao', [AnteprojetoAdminController::class, 'storeDecisao'])->name('anteprojetos.decisao');

        // Campanhas
 
        Route::post('/campanhas/{campanha}/estudantes', [CampanhaController::class, 'addEstudante'])->name('campanhas.estudantes.store');
        Route::delete('/campanhas/{campanha}/estudantes/{estudante}', [CampanhaController::class, 'desaEstudante'])->name('campanhas.estudantes.destroy');

        // Vinculação Estudante <-> Tutor
        Route::post('/estudantes/{estudante}/tutores', [EstudanteTutorController::class, 'vincularTutores'])->name('estudantes.tutores.vincular');
        Route::delete('/estudantes/{estudante}/tutores/desvincular', [EstudanteTutorController::class, 'desvincular'])->name('estudantes.tutores.desvincular');
        Route::post('/tutores/toggle/{professor_id}', [TutorController::class, 'toggle'])->name('tutores.toggle');

        //departamentos
        Route::get('/departamentos/{departamento}/professores', [DepartamentoController::class, 'professorPorDepartamento'])
    ->name('departamentos.professores');
        //cursos
        Route::get('/cursos/{curso}/campanhas', [CampanhaController::class, 'indexPorCurso'])->name('cursos.campanhas');
        Route::get('/cursos/{curso}/campanhas/create', [CampanhaController::class, 'cursoCampanhaCreate'])->name('cursos.campanhas.create');
        Route::get('/cursos/{curso}/estudantes', [CursoController::class, 'estudantePorCurso'])
    ->name('cursos.estudantes');
     Route::get('/cursos/{curso}/estudantes/create', [CursoController::class, 'estudantePorCursoCreate'])
    ->name('cursos.estudantesCreate');

        // Recursos Básicos
        Route::resource('departamentos', DepartamentoController::class);
        Route::resource('cursos', CursoController::class);
        Route::resource('professores', ProfessorController::class)->parameters(['professores' => 'professor']);
        Route::resource('estudantes', EstudanteController::class);
        Route::resource('areasCientificas', AreaCientificaController::class)-> middleware(['roles:secretaria|admin']);
        
        Route::resource('coordenadores', CoordenadorController::class)->parameters(['coordenadores' => 'coordenador'])-> middleware(['roles:secretaria|admin']);
    
    Route::resource('secretarias', SecretariaController::class)-> middleware(['roles:admin']);
        });

});

require __DIR__.'/auth.php';