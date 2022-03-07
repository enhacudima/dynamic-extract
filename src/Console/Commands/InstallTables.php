<?php

namespace Enhacudima\DynamicExtract\Console\Commands;

use Illuminate\Console\Command;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewTables;
use Illuminate\Support\Facades\Schema;

class InstallTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-extract:tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure DynamicExtract tables';

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
        $this->info('Are you about insert new table to DynamicExtract');
        $table_name = $this->ask('What is your table name?');
        $check_table = Schema::connection(config('dynamic-extract.db_connection'))->hasTable($table_name);
        if(!$check_table){
            $this->error("Could not find any related tables");
            $table_name = $this->ask('What is table name?');
        }
        $name = $this->ask('What is table screm name?');
        $can = $this->ask('What is permission?');

        ReportNewTables::updateorcreate(
            [
                'table_name' => $table_name,
            ],
            [
            'name' => $name,
            'can' => $can,
        ]);


        $this->info('DynamicExtract insert new table successfully');
        return Command::SUCCESS;
    }
}
