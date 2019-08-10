<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class vuejsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:vuejs {name} {--type=null} {--clear=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new vue js file';

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
        $list = ['form'];
        $name = $this->argument('name');
        $clear = ($this->option('clear'))?false:true; 
        $type = (in_array($this->option('type'),$list))?$this->option('type'):'default';
        $this->stubFile($name,$type);
        if($clear == false){
            $this->addRoute($name);
            $routename = "admin.".str_replace('/','.',$name);
            $this->info('route-link [ :to="{name:\''.$routename.'\'}" ]'); 
        }
        $this->info('Done !');
    }

    public function stubFile($name,$type){ 
        $stub = file_get_contents(resource_path('stub/vuejs/').$type.'.stub');
        $data = str_replace(
            ['{name}','{type}'],
            [$name,$type],
            $stub
        );
        $file = resource_path('assets/js/components/').$name.'Component.vue';
        if(is_file($file)){
            $this->error('Error : vuejs exist !');
            exit;
        } 
        $path = str_replace(last(explode('/',$file)),'',$file);
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true); 
        file_put_contents($file,$data);
        $this->line('- vuejs file '.$name.'Component.vue');
    }

    public function addRoute($name){ 
        $routeJsFile = resource_path('assets/js/routes.js');
        $VueName = "";
        $listName = explode('/',$name); 
        foreach ($listName as $key => $iname) {
            $VueName = ucfirst($iname).$VueName;
        }
        $data = file_get_contents($routeJsFile);
        $array = explode('export const routes = [',$data);
        $array[0] .= "\r\nimport ".$VueName." from './components/".$name."Component';";
        $array[0] .= "\r\nexport const routes = [";
        $array[1] = str_replace('];','',$array[1]);
        $array[1] .= "    {
        path:'/admin/".$name."',
        component: ".$VueName.",
        name: 'admin.".str_replace('/','.',$name)."'
    },\r\n
];";
        file_put_contents($routeJsFile,$array[0].$array[1]);
        
        $this->line('- add component to route.js');

    }

}
