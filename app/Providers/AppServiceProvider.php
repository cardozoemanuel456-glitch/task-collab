<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        View::composer('paginas.app', function ($view) {
            if (auth()->check()) {
                $view->with([
                    'paginasPrivadas' => auth()->user()->paginas()->whereNull('padre_id')->latest()->get(),
                    'paginasColaborativas' => auth()->user()->paginasCompartidas()->whereNull('padre_id')->get(),
                ]);
            } else {
                $view->with([
                    'paginasPrivadas' => collect(),
                    'paginasColaborativas' => collect(),
                ]);
            }
        });
    }
}
