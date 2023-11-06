<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportNewApiExternalPushData extends Model
{
    use SoftDeletes;
    protected $table = 'report_new_api_external_push_data';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=true;
    public function table()
    {
        return $this->belongsTo(ReportNewTables::class, 'table_name', 'id');
    }

}
