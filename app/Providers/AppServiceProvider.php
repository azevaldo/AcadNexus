<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
}
}
