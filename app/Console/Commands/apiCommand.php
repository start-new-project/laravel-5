<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class apiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api {name} {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create new api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //make api controller {name,model}
        $name = $this->argument('name');
        $model = $this->argument('model'); 
        $this->line('-------------------------------------------');
        $this->makeResource($name);
        $this->makeRequests($model);
        $this->makeController($name,$model); 
        $this->addRoute($name);
        $this->info('Done !');
        $this->line('-------------------------------------------');
    }


    public function makeResource($name){
        Artisan::call('make:resource '.ucfirst($name).'Resources');
        $this->line('- Resource created : '.ucfirst($name).'Resources');
    }


    public function makeRequests($model){
        $list = ['Store','Update']; 
        $this->line('- Request created : ');
        foreach ($list as $item) {
            Artisan::call('make:request '.ucfirst($model).'/'.$item.'Request');
            $requests[] = ucfirst($model).'/'.$item.'Request';
            $this->line('   * '.ucfirst($model).'/'.$item.'Request');
        } 
    }


    public function makeController($name,$model){
        //$this->artisan('make:controller API/'.$name.' -m Models/'.$model); 
        $stub = file_get_contents(resource_path('stub/api/').'controller.stub');
        $data = str_replace(
            ['{model}','{name}','{modelvar}'],
            [$model,ucfirst($name),strtolower($model)],
            $stub
        );
        $file = app_path('Http/Controllers/API/').ucfirst($name).'Controller.php';
        file_put_contents($file,$data);
        $this->line('- Controller created : '.ucfirst($name).'Controller');

    }


    public function addRoute($name){ 
        $fileRoute = base_path('routes/').'api.php'; 
        $routes = file_get_contents($fileRoute); 
        $routes .= "\r\nRoute::resource('".strtolower($name)."','API\\".ucfirst($name)."Controller')->except(['create','show']);";
        file_put_contents($fileRoute,$routes);
        $this->line('- Route created');
    }


}
