<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    \App\Models\AnteprojetoVersao::observe(\App\Observers\AnteprojetoVersaoObserver::class);
    \App\Models\DecisaoAnteprojeto::observe(\App\Observers\DecisaoAnteprojetoObserver::class);
    \App\Models\SubmissaoFase::observe(\App\Observers\SubmissaoFaseObserver::class);
    \App\Models\AvaliacaoFase::observe(\App\Observers\AvaliacaoFaseObserver::class);

    // Força o Carbon a utilizar o idioma português
        Carbon::setLocale('pt');
        
        // Opcional: Configura o locale em nível de sistema operacional (tenta variantes comuns)
        setlocale(LC_TIME, 'pt_PT.utf-8', 'pt_PT', 'portuguese', 'pt_BR.utf-8', 'pt_BR');
}
}
