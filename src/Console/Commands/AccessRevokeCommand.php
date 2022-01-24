<?php

namespace Enhacudima\DynamicExtract\Console\Commands;

use Illuminate\Console\Command;
use Enhacudima\DynamicExtract\DataBase\Model\User;

class AccessRevokeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-extract:access-revoke {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure DynamicExtract revoke user access';

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
     * @param  \App\Support\DripEmailer  $drip
     * @return mixed
     */
    public function handle()
    {
        $user = $this->argument('user');
        
        $check = User::where('email',$user)->first();
        
        if(!$check){
            $this->error("Could not find any related access");
            $email = $this->ask('What is revoke email?');
        }elseif ($this->confirm('Do you wish to revoke access to '.$user.'?')) {
            User::where('email',$user)->delete();
            $this->info('Revoked link access: '. $user);
        }
        return Command::SUCCESS;
    }
}
