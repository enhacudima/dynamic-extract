<?php

namespace Enhacudima\DynamicExtract\Console\Commands;

use Illuminate\Console\Command;
use Enhacudima\DynamicExtract\DataBase\Model\Access;

class AccessListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-extract:access-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure DynamicExtract list users access';

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
        $this->info('DynamicExtract Access list');

        $this->table(
            ['Code', 'Table name', 'Email', 'Updated','Expire','Access'],
            Access::all(['id', 'name','email', 'updated_at','expire_at', 'access_link'])->toArray()
        );
        return Command::SUCCESS;
    }
}
