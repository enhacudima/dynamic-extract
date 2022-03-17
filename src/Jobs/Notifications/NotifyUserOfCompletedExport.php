<?php
namespace Enhacudima\DynamicExtract\Jobs\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Enhacudima\DynamicExtract\Notifications\ExportReady;
use Enhacudima\DynamicExtract\DataBase\Model\ProcessedFiles;
use Storage;

class NotifyUserOfCompletedExport implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $filename;

    public function __construct($user = null,$filename)
    {
        $this->user = $user;
        $this->filename=$filename;
    }

    public function handle()
    {
        ProcessedFiles::where('filename',$this->filename)
        ->update([
            'status' => 0,
        ]);
        $this->user->notify(new ExportReady($this->filename));
    }
}
