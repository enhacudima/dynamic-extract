<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;
use App\ReportNewFiltro;
use Enhacudima\DynamicExtract\DataBase\Model\ReportFavorites;

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
        public function favorite()
    {
        return $this->belongsTo(ReportFavorites::class,'id', 'report_id');
    }


        public function table()
    {
        return $this->belongsTo(ReportNewTables::class,'table_name','id');
    }

        public function user()
    {
        return $this->belongsTo(config('dynamic-extract.middleware.model'),'user_id','id');
    }

        public function filtro_r()
    {
        return $this->belongsTo(ReportNewFiltroGroupo::class,'filtro','id');
    }

}
