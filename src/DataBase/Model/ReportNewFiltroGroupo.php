<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;

class ReportNewFiltroGroupo extends Model
{
    protected $table = 'report_new_filtro_group';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=true;


    public function sync_filtros()
    {
        return $this->hasMany(ReportNewSyncFiltro::class,'groupo_filtro','id');
    }
    public function user()
    {
        return $this->belongsTo(config('dynamic-extract.middleware.model'),'user_id','id');
    }

}
