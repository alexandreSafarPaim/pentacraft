<?php

namespace Alexandresafarpaim\Pentacraft\Console\Commands;

use Illuminate\Console\Command;

class CraftModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pcraft:model {name : Nome do Model} {--m|migration : Criar uma Migration} {--c|controller : Criar um Controller} {--s|soft : Criar um SoftDelete}}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Modelo Base Pentagrama] Cria um Model com fillable, casts e scopeFilter. Opcionalmente pode cria uma Migration, um Controller e/ou um SoftDelete.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        //prevent console alert

        $name = $this->argument('name');
        $migration = $this->option('migration');
        $controller = $this->option('controller');
        $soft = $this->option('soft');

        if(file_exists(app_path("Models/{$name}.php"))){
            echo "\n   \e[41m ERROR \e[0m\e[49m\e[97m Model {$name} já existe! \e[0m\n";
            return;
        }

        //Model

        $defaultPath = __DIR__ . '/../../templates/Model.template';
        $file = env('PENTACRAFT_MODEL', $defaultPath);
        if($file == "") $file = $defaultPath;
        $content = file_get_contents($file);
        $content = preg_replace('/@@name/i', $name, $content);
        if($soft){
            $content = preg_replace('/@@soft_import/i', "use Illuminate\Database\Eloquent\SoftDeletes;", $content);
            $content = preg_replace('/@@soft_use/i', "use SoftDeletes;", $content);
        }

        $content = preg_replace('/@@.*/i', '', $content);
        $content = preg_replace('/\n\n\n/i', "\n\n", $content);

        $file = app_path("Models/{$name}.php");
        file_put_contents($file, $content);

        echo "\n   \e[104m INFO \e[0m\e[49m\e[97m app/Model ". $name ." criado em: Models/{$name}.php! \e[0m\n";


        //Migration

        if($migration){
            $this->callSilent('make:migration', ['name' => "create_". $this->pluralize($this->snakeCase($name)) ."_table"]);
        }
        if($soft){
            $file = glob(database_path('migrations/*create_'. $this->pluralize($this->snakeCase($name)) .'_table.php'))[0];
            $content = file_get_contents($file);
            $content = preg_replace('/\$table->id\(\);/i', "\$table->id();\n            \$table->softDeletes();", $content);
            file_put_contents($file, $content);
        }
        echo "\n   \e[104m INFO \e[0m\e[49m\e[97m Migration database/migrations/create_". $this->pluralize($this->snakeCase($name)) ."_table.php criada! \e[0m\n";


        //Controller
        if($controller){
            $this->call('pcraft:controller', ['name' => "{$name}Controller", '--model' => false, '--soft' => $soft]);
        }


    }

    public function snakeCase($string) {
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
