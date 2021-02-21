<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\PrivateMessage;

class SendPrivateMessage extends Command
{
    protected $signature = 'send:msg { user_id } { message }';

    protected $description = 'Send message to specific user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $user_id = $this->argument('user_id');
        $msg = $this->argument('message');
        
        event(new PrivateMessage($user_id, $msg));

        //broadcast(new PrivateMessage($user_id, $msg)); //->toOthers();

        $this->info("Message sent on channel news.".$user_id);
    }
}



