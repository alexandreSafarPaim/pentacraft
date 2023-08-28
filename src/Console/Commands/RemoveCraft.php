<?php

namespace Alexandresafarpaim\Pentacraft\Console\Commands;

use Illuminate\Console\Command;

class RemoveCraft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pcraft:remove {name : Nome do Model a ser removido} {--m|migration : Remover a Migration} {--c|controller : Remover o Controller} {--controller-only : Remover apenas o Controller} {--migration-only : Remover apenas a Migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove um Model, uma Migration e/ou um Controller.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $migration = $this->option('migration');
        $controller = $this->option('controller');
        $controllerOnly = $this->option('controller-only');
        $migrationOnly = $this->option('migration-only');

        if($migrationOnly){
            $this->removeMigration($name);
            return;
        }

        if($controllerOnly){
            $this->removeController($name);
            return;
        }

        if($migration){
            $this->removeMigration($name);
        }

        if($controller){
            $this->removeController($name);
        }

        $this->removeModel($name);
    }

    public function removeMigration($name){
        $migration = $this->pluralize($this->snakeCase($name));
        $migration = "create_{$migration}_table.php";
        $migrationFile = glob(database_path("migrations/*{$migration}"));
        if(count($migrationFile) > 0){
            $migrationFile = $migrationFile[0];
            unlink($migrationFile);
            echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Migration {$migration} removida! \e[0m\n";
        }else{
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Migration {$migration} não encontrada! \e[0m\n";
        }
    }

    public function removeController($name){
        $controller = $name . 'Controller';
        $controller = "{$controller}.php";
        $controllerFile = glob(app_path("Http/Controllers/*{$controller}"));
        if(count($controllerFile) > 0){
            $controllerFile = $controllerFile[0];
            unlink($controllerFile);
            echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Controller {$controller} removido! \e[0m\n";
        }else{
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Controller {$controller} não encontrado! \e[0m\n";
        }

        $resource = $name . 'Resource';
        $resource = "{$resource}.php";
        $resourceFile = glob(app_path("Http/Resources/*{$resource}"));
        if(count($resourceFile) > 0){
            $resourceFile = $resourceFile[0];
            unlink($resourceFile);
            echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Resource {$resource} removido! \e[0m\n";
        }else{
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Resource {$resource} não encontrado! \e[0m\n";
        }

        $createRequest = 'Create' . $name . 'Request';
        $createRequest = "{$createRequest}.php";
        $createRequestFile = glob(app_path("Http/Requests/*{$createRequest}"));
        if(count($createRequestFile) > 0){
            $createRequestFile = $createRequestFile[0];
            unlink($createRequestFile);
            echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Request {$createRequest} removido! \e[0m\n";
        }else{
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Request {$createRequest} não encontrado! \e[0m\n";
        }

        $updateRequest = 'Update' . $name . 'Request';
        $updateRequest = "{$updateRequest}.php";
        $updateRequestFile = glob(app_path("Http/Requests/*{$updateRequest}"));
        if(count($updateRequestFile) > 0){
            $updateRequestFile = $updateRequestFile[0];
            unlink($updateRequestFile);
            echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Request {$updateRequest} removido! \e[0m\n";
        }else{
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Request {$updateRequest} não encontrado! \e[0m\n";
        }

        $routes = file_get_contents(base_path('routes/api.php'));
        $routes = str_replace("\nRoute::apiResource('".$this->snakeCase($name)."', \App\Http\Controllers\\{$name}Controller::class);\n", '', $routes);
        $routes = str_replace("Route::put('".$this->snakeCase($name)."/restore/{id}', [\App\Http\Controllers\\{$name}Controller::class, 'restore']);\n", '', $routes);
        file_put_contents(base_path('routes/api.php'), $routes);
    }

    public function removeModel($name){
        $model = $name . '.php';
        $modelFile = glob(app_path("Models/*{$model}"));
        if(count($modelFile) > 0){
            $modelFile = $modelFile[0];
            unlink($modelFile);
            echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Model {$model} removido! \e[0m\n";
        }else{
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Model {$model} não encontrado! \e[0m\n";
        }
    }

    public function snakeCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }


    public function pluralize($singular) {
        $last_letter = strtolower($singular[strlen($singular)-1]);
        switch($last_letter) {
            case 'y':
                return substr($singular,0,-1).'ies';
            case 's':
                return $singular.'es';
            default:
                return $singular.'s';
        }
    }
}
