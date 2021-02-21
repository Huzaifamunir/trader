<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\MessageCreateBroadcastEvent;

class broadcast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcast:msg {message}';

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
        $msg = $this->argument('message');
        
        //broadcast(new MessageCreateBroadcastEvent($msg)); //->toOthers();

        event(new MessageCreateBroadcastEvent($msg));


        $this->info("Event broadcasted.");
    }
}



