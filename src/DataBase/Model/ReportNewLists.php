<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;

class ReportNewLists extends Model
{
    protected $table = 'report_new_list';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=true;

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

        public function filtro()
    {
        return $this->belongsTo(ReportNewFiltro::class,'report_new_filtro_id','id');
    }

}
