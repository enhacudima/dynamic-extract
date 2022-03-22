<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;

class ReportNewColumuns extends Model
{
    protected $table = 'report_new_columuns';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=true;

        public function user()
    {
        return $this->belongsTo(config('dynamic-extract.middleware.model'),'user_id','id');
    }

        public function filtro()
    {
        return $this->belongsTo(ReportNewFiltro::class,'report_new_filtro_id','id');
    }


}
