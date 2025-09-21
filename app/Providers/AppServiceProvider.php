<?php

namespace App\Providers;

use App\Models\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Compartilhar dados da config globalmente
        View::composer('*', function ($view) {
            try {
                $config = Config::first();
                $view->with('globalConfig', $config);
            } catch (\Exception $e) {
                // Em caso de erro (ex: tabela não existe ainda), usar valores padrão
                $view->with('globalConfig', (object)[
                    'nome_pizzaria' => 'Integra Pizzaria'
                ]);
            }
        });
    }
}
