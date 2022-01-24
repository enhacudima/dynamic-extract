<?php

namespace Enhacudima\DynamicExtract\Console\Commands;

use Illuminate\Console\Command;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewTables;
use Illuminate\Support\Facades\Schema;

class InstallTablesList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-extract:tables-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure DynamicExtract tables list';

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
        $this->info('DynamicExtract table list');

        $this->table(
            ['Code', 'Table name', 'Screen Name', 'Permission'],
            ReportNewTables::all(['id', 'table_name', 'name', 'can'])->toArray()
        );

        if ($this->confirm('Do you wish to update table?')) {
            $id = $this->ask('What is table code?');
            $id_check = ReportNewTables::find($id);
            if(!$id_check){
                $this->error("Could not find any related tables");
                $id = $this->ask('What is table code?');
            }
            $table_name = $this->ask('What is table name?');
            $check_table = Schema::hasTable($table_name);
            if(!$check_table){
                $this->error("Table not find");
                $table_name = $this->ask('What is table name?');
            }
            $name = $this->ask('What is table screen name?');
            $can = $this->ask('What is permission?');

            ReportNewTables::where('id', $id)
            ->update([
                'table_name' => $table_name,
                'name' => $name,
                'can' => $can,
            ]);
            $this->info('DynamicExtract table deleted successfully');
        }


        if ($this->confirm('Do you wish to delete table?')) {
            $id = $this->ask('What is table code?');
            $id_check = ReportNewTables::find($id);
            if(!$id_check){
                $this->error("Could not find any related tables");
                $id = $this->ask('What is table code?');
            }


            ReportNewTables::where('id', $id)
            ->delete();
            $this->info('DynamicExtract table successfully updated');
        }

        $this->info('All done');
        return Command::SUCCESS;
    }
}
