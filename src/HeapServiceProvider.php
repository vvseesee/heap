<?php

namespace Vvseesee\Heap;

use Illuminate\Support\ServiceProvider;

class HeapServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Heap::class, function ($app) {
            return new Heap($app['config']);
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Config/heap.php' => config_path('heap.php'),
            ]);
        }
    }

    public function provides()
    {
        return [Heap::class];
    }
}
