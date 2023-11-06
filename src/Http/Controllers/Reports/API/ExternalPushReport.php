<?php

namespace Enhacudima\DynamicExtract\Http\Controllers\Reports\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Enhacudima\DynamicExtract\DataBase\Model\Access;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewApiExternalPushData;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewApiExternalSchedule;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ExternalPushReport extends Controller
{

    public $user_id;
    public $user_model;
    public $prefix;
    public $maxPage = 2;
    public function __construct()
    {
        $this->prefix = config('dynamic-extract.prefix');
    }


    public function index($uuid)
    {
        $table = $this->signIn($uuid);

        if($table->advance_query == 1){
            $test = $this->validateQuery($table->text_query);
            if (!$test)
                abort(401);
                try {
                    $query = DB::connection(config('dynamic-extract.db_connection'))->select($table->text_query);
                } catch (\Throwable $th) {
                    abort(401);
                }
            if($table->paginate == 1){
                $data = new Paginator($query, $this->maxPage);
            }else {
                $data = $query;
            }
        }else {
            $query = DB::connection(config('dynamic-extract.db_connection'))->table($table->table->table_name);
            if ($table->paginate == 1) {
                $data = $query->paginate($this->maxPage);
            } else {
                $data = $query->get();
            }
        }
        return response()->json($data);
    }
    public function signIn($uuid)
    {
        // Authenticate the user
        $date = Carbon::today();
        $user = ReportNewApiExternalPushData::query()
            ->with('table')
            ->where('access_link',$uuid)
            ->where('status', 1)
            ->where('expire_at','<=', $date)
            ->firstOrFail();

        $this->checkSchedule($user->id);

        return $user;
    }


    public function checkSchedule($report_id)
    {
        // Authenticate the user
        $schedule = ReportNewApiExternalSchedule::query()
            ->where('report_id', $report_id)
            ->where('status', 1)
            ->orderby('time_end','asc')
            ->get();

        $status = true;
        if(isset($schedule)){
            foreach ($schedule as $key => $value) {
                if($value->method == "Allow") {
                    $now = Carbon::now();
                    $start = Carbon::createFromTimeString($value->time_start);
                    $end = Carbon::createFromTimeString($value->time_end);
                    if ($now->between($start, $end)) {
                        $status = true;
                    }else{
                        $status = false;
                    }
                }
            }
            foreach ($schedule as $key => $value) {
                if ($value->method != "Allow") {
                    $now = Carbon::now();
                    $start = Carbon::createFromTimeString($value->time_start);
                    $end = Carbon::createFromTimeString($value->time_end);
                    if ($now->between($start, $end)) {
                        $status = false;
                    } else {
                        $status = true;
                    }
                }
            }
        }

        if($status == false){
            abort(401);
        }
        return true;
    }


    public function validateQuery($query)
    {
        return true;
    }

    public function getTimeDiff()
    {

    }


}
