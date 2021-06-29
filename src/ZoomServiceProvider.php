<?php

namespace Binay\Zoom;

use Illuminate\Support\ServiceProvider;
class ZoomServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/zoom.php','zoom');
        $this->publishes([
            __DIR__.'/config/zoom.php' => config_path('zoom.php')
        ]);
    }

    public function register()
    {

    }
}