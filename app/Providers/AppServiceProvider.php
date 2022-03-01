<?php

namespace App\Providers;

use App\Api\Core\Helpers\FileHelper;
use App\Api\Core\Helpers\PathHelper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Will use the custom path helper to load migrations from the custom path
        $pathHelper = new PathHelper();
        $directories = glob($pathHelper->getMigrationsPath() . '/*' , GLOB_ONLYDIR);
        $paths = array_merge([$pathHelper->getMigrationsPath()], $directories);
        $this->loadMigrationsFrom($paths);
    }
}
