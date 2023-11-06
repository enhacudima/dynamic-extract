<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportNewApiExternalSchedule extends Model
{
    use SoftDeletes;
    protected $table = 'report_new_api_external_schedule';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=true;
    public function report()
    {
        return $this->belongsTo(ReportNewApiExternalPushData::class, 'report_id', 'id');
    }

}
