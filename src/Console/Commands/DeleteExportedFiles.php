<?php

namespace Enhacudima\DynamicExtract\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Enhacudima\DynamicExtract\DataBase\Model\ProcessedFiles;
use Storage;

class DeleteExportedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dynamic-extract:delete-exported';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete all exported files';

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


        $files=ProcessedFiles::get();

        foreach ($files as $file)
        {
          Storage::delete($file->path.$file->filename);
        }
        ProcessedFiles::truncate();

        $this->info('delete all exported files sucessfuly');
    }
}
