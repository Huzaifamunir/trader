<?php

namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;

class create_view extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:view {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create view for specific model';

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
        $model = $this->argument('model');
        $src   = resource_path('views/resource_sample');
        $dest  = resource_path('views/'.$model);

        // Create folder with model name
        //$result = File::makeDirectory($dest);

        // Create files
        //$index  = File::copy($src."/index.blade.php", $dest."/index.blade.php");
        //$single = File::copy($src."/single.blade.php", $dest."/single.blade.php");
        //$form   = File::copy($src."/form.blade.php", $dest."/form.blade.php");
        //$print  = File::copy($src."/print.blade.php", $dest."/print.blade.php");

        $this->info("View: $model created with index, single and form files.");
    }
}
