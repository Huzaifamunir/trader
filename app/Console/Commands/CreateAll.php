<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create_all_models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all models with resource controllers, model factories and migrations';

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
        $models=[
            'test1',
            'test2',
            'test3',
            'test4',
            'test5',
        ];

        foreach($models as $model){
            // $this->call('make:model', [
            //     'name' => $this->argument('name'),
            //     '-m' => 'true',
            //     '-c' => 'true',
            //     '-r' => 'true',
            // ]);
            
            $this->info($model." -mcr");
        }


        $this->info("|||||||||||||||| Success");
    }
}
