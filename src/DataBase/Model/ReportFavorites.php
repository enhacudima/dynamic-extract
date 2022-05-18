<?php

namespace Enhacudima\DynamicExtract\DataBase\Model;

use Illuminate\Database\Eloquent\Model;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNew;

class ReportFavorites extends Model
{
    protected $table = 'report_new_favorites';
    protected $guarded =array();

    public $primaryKey = 'id';

    public $timestamps=true;

    public function report()
    {
        return $this->belongsTo(ReportNew::class,'report_id','id');
    }

    static function Favorite ($user_id, $user_class, $report)
    {
        self::UpdateOrCreate(
            [
                'user_id'=>$user_id,
                'user_model'=>$user_class,
                'report_id'=>$report
            ],
            [

            ]
        );
    }

}
