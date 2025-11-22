<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the Face Recognition Service
        $this->app->singleton('face.recognition', function ($app) {
            return new \App\Services\FaceRecognitionService();
        });
    }

    public function boot()
    {
        //
    }
}