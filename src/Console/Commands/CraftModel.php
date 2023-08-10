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
        // create a model file in app/Models called {name}.php and wait for it to finish
        $name = $this->argument('name');
        $migration = $this->option('migration');
        $controller = $this->option('controller');
        $soft = $this->option('soft');
        $this->call('make:model', ['name' => "{$name}"]);
        if($migration){
            $this->call('make:migration', ['name' => "create_". $this->pluralize(strtolower($name)) ."_table"]);
        }

        if($soft){
            $file = glob(database_path('migrations/*create_'. $this->pluralize(strtolower($name)) .'_table.php'))[0];
            $content = file_get_contents($file);
            $content = preg_replace('/\$table->id\(\);/i', "\$table->id();\n            \$table->softDeletes();", $content);
            file_put_contents($file, $content);
        }

        if($controller){
            $this->call('pcraft:controller', ['name' => "{$name}Controller"]);
        }


        $file = app_path("Models/{$name}.php");
        $content = file_get_contents($file);
        $content = preg_replace('/use Illuminate\\\\Database\\\\Eloquent\\\\Model;/i', "use Illuminate\Database\Eloquent\Model;\nuse Illuminate\Database\Eloquent\Builder;", $content);
        if($soft){
            $content = preg_replace('/use Illuminate\Database\Eloquent\Builder;/i', "use Illuminate\Database\Eloquent\Builder;\nuse Illuminate\Database\Eloquent\SoftDeletes;", $content);
            $content = preg_replace('/use HasFactory;/i', "use SoftDeletes;\n\nuse HasFactory;", $content);
        }
        $content = preg_replace('/use HasFactory;/i', "use HasFactory;\n\n" .$this->createFillable(). "\n\n". $this->createCasts(). "\n\n". $this->createScopeFilter(), $content);
        file_put_contents($file, $content);

    }

    private function createFillable(){
        $fillableString = "        protected \$fillable = [
            ];";

        return $fillableString;
    }

    private function createCasts(){
        $castsString = "        protected \$casts = [
            ];";

        return $castsString;
    }

    private function createScopeFilter(){
        $scopeString = "        public function scopeFilter(Builder \$query, array \$filters)
        {
            foreach (\$filters as \$filter => \$value) {
                if (\$value === null) {
                    continue;
                }
                \$query->where(\$filter, \$value);
            }

            return \$query;
        }";
        return $scopeString;
    }

    public static function pluralize($singular) {
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
