<?php

namespace Enhacudima\DynamicExtract\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-extract:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure DynamicExtract';

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
        if ($this->confirm('Its always good to run Migration on first install, Do you wish to continue?')) {
            $this->call('migrate', [
                '--path' => '/packages/enhacudima/dynamic-extract/src/Database/Migration'
            ]);
        }
        $this->call('vendor:publish', [
            '--tag' => 'public','--force' => true
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Enhacudima\DynamicExtract\Providers\DynamicExtractServiceProvider','--tag' => 'config'
        ]);
        $this->call('storage:link');
        $this->call('config:cache');
        $this->call('dynamic-extract:tables');
        $this->call('dynamic-extract:access');

        $this->info('DynamicExtract installed successfully ');
        return Command::SUCCESS;
    }
}
