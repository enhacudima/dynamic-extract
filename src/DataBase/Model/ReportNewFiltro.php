<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;

class ReportNewFiltro extends Model
{
    protected $table = 'report_new_filtro';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=true;

        public function lists()
    {
        return $this->hasMany(ReportNewLists::class,'report_new_filtro_id','id');
    }

        public function columuns()
    {
        return $this->hasMany(ReportNewColumuns::class,'report_new_filtro_id','id');
    }
        public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

}
