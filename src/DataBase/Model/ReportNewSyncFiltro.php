<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;

class ReportNewSyncFiltro extends Model
{
    protected $table = 'report_new_filtro_sync_group';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=false;


    public function filtros()
    {
        return $this->belongsTo(ReportNewFiltro::class,'filtro','id');
    }

}
