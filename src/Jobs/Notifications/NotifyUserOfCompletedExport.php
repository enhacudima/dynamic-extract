<?php
namespace Enhacudima\DynamicExtract\Jobs\Notifications;

use App\User;
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

    public function __construct(User $user = null,$filename)
    {
        $this->user = $user;
        $this->filename=$filename;
    }

    public function handle()
    {
        $this->user->notify(new ExportReady($this->filename));
    }
}
