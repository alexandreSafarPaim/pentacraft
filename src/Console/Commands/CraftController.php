<?php

namespace Alexandresafarpaim\Pentacraft\Console\Commands;

use Illuminate\Console\Command;

class CraftController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pcraft:controller {name : Nome do controller} {--m|model : Criar um Model} {--s|soft : Adiciona funções de SoftDelete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Modelo Base Pentagrama] Cria um controller pronto para uma aplicação api-rest com resource, request e rotas. Opcionalmente pode criar um model.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');

        if(strpos($name, 'Controller') !== false){
            $name = str_replace('Controller', '', $name);
        }

        $model = $this->option('model');
        $soft = $this->option('soft');

        if($model){
            $this->call('pcraft:model', ['name' => "{$name}", '--migration' => true, '--controller' => false, '--soft' => $soft]);
        }

        //verificar se o resource existe

        if(file_exists(app_path("Http/Resources/{$name}Resource.php"))){
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Resource {$name}Resource já existe! \e[0m\n";
        }else{
            $this->callSilent('make:resource', ['name' => "{$name}Resource"]);
            echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Resource {$name}Resource criado em app/Http/Resources/{$name}Resource.php! \e[0m\n";
        }

        if(file_exists(app_path("Http/Requests/Create{$name}Request.php"))){
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Request Create{$name}Request já existe! \e[0m\n";
        }else{
            $this->callSilent('make:request', ['name' => "Create{$name}Request"]);
            $fileCreateRequest = app_path("Http/Requests/Create{$name}Request.php");
            $contentCreateRequest  = file_get_contents($fileCreateRequest);
            $contentCreateRequest  = preg_replace('/return false;/i', "return true;", $contentCreateRequest );
            file_put_contents($fileCreateRequest, $contentCreateRequest );
            echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Request Create{$name}Request criado em app/Http/Requests/Create{$name}Request.php! \e[0m\n";
        }

        if(file_exists(app_path("Http/Requests/Update{$name}Request.php"))){
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Request Update{$name}Request já existe! \e[0m\n";
        }else{
            $this->callSilent('make:request', ['name' => "Update{$name}Request"]);
            $fileUpdateRequest = app_path("Http/Requests/Update{$name}Request.php");
            $contentUpdateRequest  = file_get_contents($fileUpdateRequest);
            $contentUpdateRequest  = preg_replace('/return false;/i', "return true;", $contentUpdateRequest );
            file_put_contents($fileUpdateRequest, $contentUpdateRequest );
            echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Request Update{$name}Request criado em app/Http/Requests/Update{$name}Reques.php! \e[0m\n";
        }

        //Cobtroller

        if(file_exists(app_path("Http/Controllers/{$name}Controller.php"))){
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Controller {$name}Controller já existe! \e[0m\n";
            return;
        }

        $defaultPath = __DIR__ . '/../../templates/Controller.template';
        $file = env('PENTACRAFT_CONTROLLER', $defaultPath);
        if($file == "") $file = $defaultPath;
        $content = file_get_contents($file);
        $content = preg_replace('/@@model_import/i', "use App\Models\\{$name};", $content);
        $content = preg_replace('/@@resource_import/i', "use App\Http\Resources\\{$name}Resource;", $content);
        $content = preg_replace('/@@request_import/i', "use App\Http\Requests\Create{$name}Request;\nuse App\Http\Requests\Update{$name}Request;", $content);

        $content = preg_replace('/@@controller_name/i', $name.'Controller', $content);
        $content = preg_replace('/@@model_var/i', '$'. strtolower($name), $content);
        $content = preg_replace('/@@model_name/i', $name, $content);
        $content = preg_replace('/@@resource/i', $name.'Resource', $content);
        $content = preg_replace('/@@request_create/i', 'Create'.$name.'Request', $content);
        $content = preg_replace('/@@request_update/i', 'Update'.$name.'Request', $content);

        if($soft){
            //add restore function
            $content = preg_replace('/@@soft/i', $this->restoreFunction($name), $content);
        }else{
            $content = preg_replace('/@@soft/i', '', $content);
        }


        $fileController = app_path("Http/Controllers/{$name}Controller.php");
        file_put_contents($fileController, $content);

        echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Controller {$name}Controller criado em app/Http/Controllers/{$name}Controller.php! \e[0m\n";

        //add route
        $routeFile = base_path("routes/api.php");
        $routeContent = file_get_contents($routeFile);
        $routeContent .= "\nRoute::apiResource('".$this->kebabCase($name)."', \App\Http\Controllers\\{$name}Controller::class);\n";
        if($soft) {
            $routeContent .= "Route::put('".$this->kebabCase($name)."/restore/{id}', [\App\Http\Controllers\\{$name}Controller::class, 'restore']);\n";
        }
        file_put_contents($routeFile, $routeContent);

        echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Rota criada em routes/api.php! \e[0m\n";


    }

    public function snakeCase($string) {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    public function kebabCase($string) {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $string));
    }

    private function restoreFunction($name){
        return "public function restore(\$id)
    {
        \$".strtolower($name)." = {$name}::withTrashed()->findOrFail(\$id);
        \$".strtolower($name)."->restore();
        return response()->json(['message' => 'Restaurado com sucesso!']);
    }";
    }

}
