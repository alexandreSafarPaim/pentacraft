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
    protected $signature = 'pcraft:controller {name : Nome do controller} {--m|model : Criar um Model}';

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

        if($model){
            $this->call('pcraft:model', ['name' => "{$name}", '--migration' => true, '--controller' => false]);
        }

        $this->call('make:controller', ['name' => "{$name}Controller"]);
        $this->call('make:resource', ['name' => "{$name}Resource"]);
        $this->call('make:request', ['name' => "Create{$name}Request"]);
        $this->call('make:request', ['name' => "Update{$name}Request"]);

        $fileCreateRequest = app_path("Http/Requests/Create{$name}Request.php");
        $contentCreateRequest  = file_get_contents($fileCreateRequest);
        $contentCreateRequest  = preg_replace('/return false;/i', "return true;", $contentCreateRequest );
        file_put_contents($fileCreateRequest, $contentCreateRequest );

        $fileUpdateRequest = app_path("Http/Requests/Update{$name}Request.php");
        $contentUpdateRequest  = file_get_contents($fileUpdateRequest);
        $contentUpdateRequest  = preg_replace('/return false;/i', "return true;", $contentUpdateRequest );
        file_put_contents($fileUpdateRequest, $contentUpdateRequest );

        $file = app_path("Http/Controllers/{$name}Controller.php");
        $content = file_get_contents($file);
        $content = preg_replace('/use Illuminate\\\\Http\\\\Request;/i', "use Illuminate\Http\Request;\nuse App\Models\\{$name};\nuse App\Http\Resources\\{$name}Resource;\nuse App\Http\Requests\Create{$name}Request;\nuse App\Http\Requests\Update{$name}Request;", $content);
        $content = preg_replace('/{/i', "{\n\n" .$this->indexFunction($name). "\n\n". $this->showFunction($name). "\n\n". $this->createFunction($name) . "\n\n". $this->updateFunction($name) . "\n\n". $this->deleteFunction($name) . "\n\n", $content);
        file_put_contents($file, $content);

        //add route
        $routeFile = base_path("routes/api.php");
        $routeContent = file_get_contents($routeFile);
        $routeContent .= "\nRoute::apiResource('".strtolower($name)."', \App\Http\Controllers\\{$name}Controller::class);\n";
        file_put_contents($routeFile, $routeContent);
    }

    private function indexFunction($name){
        $indexFunctionString = "    public function index(Request \$request)
    {
        \$filters = \$request->all();
        \$data = $name::filter(\$filters);
        if(\$request->has('paginate')){
            \$data = \$data->paginate(\$request->paginate);
        }else{
            \$data = \$data->get();
        }
        return ".$name."Resource::collection(\$data);
    }";
        return $indexFunctionString;
    }


    private function showFunction($name){
        $showFunctionString = "    public function show(Request \$request, ".$name. " \$$name)
    {
        return new ".$name."Resource(\$$name);
    }";
        return $showFunctionString;
    }

    private function createFunction($name){
        $createFunctionString = "    public function store(Create{$name}Request \$request)
    {
        \$data = \$request->all();
        \$data = {$name}::create(\$data);
        return new {$name}Resource(\$data);
    }";
        return $createFunctionString;
    }

    private function updateFunction($name){
        $updateFunctionString = "    public function update(Update{$name}Request \$request, ".$name. " \$$name)
    {
        \$data = \$request->all();
        \${$name}->update(\$data);
        return new {$name}Resource(\$$name);
    }";
        return $updateFunctionString;
    }

    private function deleteFunction($name){
        $deleteFunctionString = "    public function destroy(Request \$request, ".$name. " \$$name)
    {
        \${$name}->delete();
        return response()->json(['message' => 'Deletado com sucesso'], 200);
    }";
        return $deleteFunctionString;
    }

}
