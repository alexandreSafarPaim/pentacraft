<?php

namespace Alexandresafarpaim\Pentacraft;

use Alexandresafarpaim\Pentacraft\Console\Commands\RemoveCraft;
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
                RemoveCraft::class,
            ]);

            $this->publishes([
                __DIR__ . '/templates' => public_path('pentacraft/templates'),
            ], 'pentacraft');
        }
    }

    public function register()
    {
        if (!file_exists(base_path('.env'))) {
            $this->error('Arquivo .env não encontrado!');
        }else{
            $env = file_get_contents(base_path('.env'));
            if(!preg_match('/PENTACRAFT_MODEL/i', $env)){
                $env .= "\nPENTACRAFT_MODEL=";
                file_put_contents(base_path('.env'), $env);
            }
            if(!preg_match('/PENTACRAFT_CONTROLLER/i', $env)){
                $env .= "\nPENTACRAFT_CONTROLLER=";
                file_put_contents(base_path('.env'), $env);
            }
        }
    }
}
