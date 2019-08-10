<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class copyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy {from} {to} {--type=null}';
    //php artisan copy users/team / copy vuejs

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy file';

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
        
        $from = $this->argument('from'); 
        $to   = $this->argument('to'); 
        $type = $this->option('type');  
        $name = str_replace(['Component.vue','.blade.php'],['',''],last(explode('/',$to))); 
        //paths
        $path = [
            'view' => resource_path('views/'),
            'vuejs' => resource_path('assets/js/components/'),
        ];
        //extens
        $ext = [
            'view' => '.blade.php',
            'vuejs' => 'Component.vue',
        ];

        if(!empty($type)){
            if(!in_array($type,array_keys($path))){
                return $this->error('type : '.$type.' undefined !');
            }else{
                $name = ($to == "null")?last(explode('/',$from)):$name.$ext[$type];
                $from = $path[$type].$from; 
                $to   = ($to == "null")?$path[$type].$name:$path[$type].$to.$ext[$type];
                if(is_file($from)){
                    $data = file_get_contents($from);
                    file_put_contents($to,$data);
                    return $this->info('Done : copy file '.$name.' from '.last(explode('/',$from)));
                }else{
                    return $this->error('file : not found !');
                }
            }
        }



        
    }
}
