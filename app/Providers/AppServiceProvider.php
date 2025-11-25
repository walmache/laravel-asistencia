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
        // Add __ function to Twig as alias of trans
        if ($this->app->bound('twig')) {
            $twig = $this->app->make('twig');
            $twig->addFunction(new \Twig\TwigFunction('__', function($key, $replace = [], $locale = null) {
                return trans($key, $replace, $locale);
            }));
        }
    }
}
