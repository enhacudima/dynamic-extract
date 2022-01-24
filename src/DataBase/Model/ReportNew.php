<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;
use App\ReportNewFiltro;

class ReportNew extends Model
{
    protected $table = 'report_new';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=true;



        public function sync_filtros()
    {
        return $this->hasMany(ReportNewSyncFiltro::class,'groupo_filtro','filtro');
    }

        public function table()
    {
        return $this->belongsTo(ReportNewTables::class,'table_name','id');
    }

        public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

        public function filtro_r()
    {
        return $this->belongsTo(ReportNewFiltroGroupo::class,'filtro','id');
    }

}
