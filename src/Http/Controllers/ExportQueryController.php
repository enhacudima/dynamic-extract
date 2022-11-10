<?php

namespace Enhacudima\DynamicExtract\Http\Controllers;

use Illuminate\Routing\Controller;
use Enhacudima\DynamicExtract\DataBase\Model\ProcessedFiles;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNew;
use DB;

class ExportQueryController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $start;
    public $end;
    public $type;
    public $filtro;
    public $request;
    public $user_id;

    public function __construct($start,$end,$type,$filtro,$request,$user_id)
    {
        $this->start=$start;
        $this->end=$end;
        $this->type=$type;
        $this->filtro=$filtro;
        $this->request=$request;
        $this->user_id=$user_id;
        $this->heading=$this->select_columuns_head($this->request);
    }

    public function query()
    {
        $data=new ProcessedFiles;
            if ($this->filtro=='no_filter') {
                try{
                $data=DB::connection(config('dynamic-extract.db_connection'))->table($this->type)->orderby($this->heading[0] ? $this->heading[0] : 'id', 'desc');
                } catch (Throwable $e) {
                    return back()->with('error','Error: '.$e->getMessage());
                }
            }else{
            try{
                $data=DB::connection(config('dynamic-extract.db_connection'))->table($this->type)->orderby($this->heading[0] ? $this->heading[0] : 'id', 'desc');
                if($this->filtro){
                    $data->whereBetween($this->filtro,[$this->start,$this->end])->orderBy($this->filtro,'desc');
                }

                $this->filter_user_id($data,$this->request);
                $this->filter_string($data,$this->request);
                $this->select_columuns($data,$this->request);
                $this->filter_select($data,$this->request);
                $this->filter_comparison($data,$this->request);
                $this->filter_group($data,$this->request);
                } catch (Throwable $e) {
                    return back()->with('error','Error: '.$e->getMessage());
                }
            }

            return ['data'=>$data,'heading'=>$this->heading];

    }

    public function filter_group($data, $request){
        $check = false;
        $columuns = [];
        $mykey = -1;
        if(isset($request['groupColumun'])){
            foreach ($request['groupColumun'] as $key => $columun) {
                if (isset($columun)) {
                    $columuns[++$mykey] = $columun;
                    $check = true;
                }
            }
            if($check = true){
                $data->groupby($columuns);
            }
            return $data;
        }

    }
    public function filter_comparison($data, $request){
        $comparisonColumun=[];
        $comparisonValue=[];
        $typeColumun=[];
        if (isset($request['comparisonColumun'])) {
            $comparisonColumun=$request['comparisonColumun'];
        }
        if (isset($request['comparisonValue'])) {
            $comparisonValue=$request['comparisonValue'];
        }

        if (isset($request['typeColumun'])) {
            $typeColumun=$request['typeColumun'];
        }


        foreach ($comparisonColumun as $key => $columun) {
            if (isset($comparisonValue[$key])) {
                if($comparisonValue[$key]!="Sem filtro"){
                    $data->where($columun,$typeColumun[$key],$comparisonValue[$key]);
                }
            }
        }

        return $data;

    }

    public function filter_string($data,$request){
        $pesquisaColumun=[];
        $pesquisaValue=[];
        if (isset($request['pesquisaColumun'])) {
            $pesquisaColumun=$request['pesquisaColumun'];
        }
        if (isset($request['pesquisaValue'])) {
            $pesquisaValue=$request['pesquisaValue'];
        }


        foreach ($pesquisaColumun as $key => $columun) {
            if (isset($pesquisaValue[$key])) {
                if($pesquisaValue[$key]!="Sem filtro"){
                    $data->where($columun,'like','%'.$pesquisaValue[$key].'%');
                }
            }
        }

        return $data;
    }

    public function filter_select($data,$request){
        $listColumun=[];
        $listValue=[];
        if (isset($request['listColumun'])) {
            $listColumun=$request['listColumun'];
        }
        if (isset($request['listValue'])) {
            $listValue=$request['listValue'];
        }

        foreach ($listColumun as $key => $columun) {
            if (isset($listValue[$key])) {
                if($listValue[$key]!="no_filter"){
                    $data->where($columun,$listValue[$key]);
                }
            }
        }

        return $data;
    }

    public function filter_user_id($data,$request){
        $report=ReportNew::find($request['report_id']);
        foreach ($report->sync_filtros as $key => $filtro) {
            if($filtro->filtros->type=='user'){
                $columun = $filtro->filtros->value;
                $data->where($columun,$this->user_id);
            }

        }
        return $data;
    }

    public function select_columuns($data,$request){
        $report=ReportNew::find($request['report_id']);
        foreach ($report->sync_filtros as $key => $filtro) {
            if($filtro->filtros->type=='columuns'){
                $columuns=[];
                $countColumuns=sizeof($filtro->filtros->columuns);
                foreach($filtro->filtros->columuns as $key => $columun){
                    $columuns[$key]=$columun->name;
                }
                $data->select($columuns);
            }

        }
        return $data;
    }

    public function select_columuns_head($request){
        $columuns='*';
        $report=ReportNew::find($request['report_id']);
        foreach ($report->sync_filtros as $key => $filtro) {
            if($filtro->filtros->type=='columuns'){
                $columuns=[];
                $countColumuns=sizeof($filtro->filtros->columuns);
                foreach($filtro->filtros->columuns as $key => $columun){
                    $columuns[$key]=$columun->name;
                }
            }

        }
        $head=DB::connection(config('dynamic-extract.db_connection'))->table($this->type)->select($columuns)->first();
        $arrrayData=collect($head)->toArray();
        $heading=array_keys($arrrayData);
        return $heading;
    }
}
