<?php

namespace LaravelVision;

use Illuminate\Support\ServiceProvider;

class VisionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/vision.php', 'vision');

        $this->app->singleton(VisionManager::class, function ($app) {
            return new VisionManager($app);
        });

        $this->app->alias(VisionManager::class, 'vision');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/vision.php' => config_path('vision.php'),
        ], 'vision-config');
    }
}


