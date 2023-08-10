<?php

namespace Alexandresafarpaim\Pentacraft;

use Illuminate\Support\ServiceProvider;
use Alexandresafarpaim\Pentacraft\Console\Commands\CraftModel;
use Alexandresafarpaim\Pentacraft\Console\Commands\CraftController;

class PentacraftServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->commands([
                CraftModel::class,
                CraftController::class,
            ]);
        }
    }

    public function register()
    {

    }
}
