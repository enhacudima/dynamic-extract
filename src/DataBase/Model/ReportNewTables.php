<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;

class ReportNewTables extends Model
{
    protected $table = 'report_new_tables';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=true;

}
