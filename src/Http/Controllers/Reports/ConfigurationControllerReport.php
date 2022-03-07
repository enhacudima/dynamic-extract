<?php

namespace Enhacudima\DynamicExtract\Http\Controllers\Reports;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewSyncFiltro;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNew;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewFiltro;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewTables;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewFiltroGroupo;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewColumuns;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewLists;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Cookie;

class ConfigurationControllerReport extends Controller
{

    public function __construct()
    {
        $this->prefix = config('dynamic-extract.prefix').'/';
        if(config('dynamic-extract.auth')){
            $this->middleware('auth');
            if(config('dynamic-extract.middleware.permission.active')){
                $this->middleware('permission:'.config('dynamic-extract.middleware.config'));
            }
        }else{
            $this->middleware(function ($request, $next) {
                $value = $request->cookie('access_user_token');
                $storage = Cookie::get('access_user_token');
                if(!$value or $value != $storage ){
                    return redirect($this->prefix);
                }
                return $next($request);
            });
        }
    }

  public function index()
  {
  	$data=ReportNew::get();
  	$filtros=ReportNewFiltroGroupo::get();
  	$tables=ReportNewTables::get();
  	return view('extract-view::report.config.index',compact('data','filtros','tables'));
  }

  public function store(Request $request)
  {
    $validatedData = $request->validate([
        'name'=>'required|string|max:70',
        'comments'=>'required|string|max:255',
        'can'=>'required|string',
        'filtro'=>'nullable|integer',
        'table_name'=>'required|integer',
        'user_id'=>'required|integer',
        ]);
    $error=$this->validate_report($request);
    if(isset($error)){
        return back()->with('error',$error);
    }

    ReportNew::create($request->all());

    return back()->with('success','You have add new report on list');

  }
  public function delete($id)
  {
  	$data=ReportNew::find($id);
  	$status=0;
  	if ($data->status==0) {
  		$status=1;
  	}

  	ReportNew::where('id',$id)
  			->update(['status'=>$status]);

  	return back()->with('success','You changed report on the list');
  }

  public function delete_report($id)
  {
  	   $data=ReportNew::find($id);
	  	if(!isset($data)){
	  		return back()->with('error','This report is no longer available');
	  	}
        $data->delete();
        return redirect($this->prefix.'report/new')->with('success','Report deleted successfully');
  }
  public function edit($id){
  	$data=ReportNew::find($id);
  	if(!isset($data)){
  		return back();
  	}
  	$filtros=ReportNewFiltroGroupo::get();
  	$tables=ReportNewTables::get();
    $permissions = '';
  	#$permissions=DB::table('permissions')->orderBy('name','asc')->get();

  	return view('extract-view::report.config.edit',compact('data','filtros','tables','permissions'));

  }
  function validate_report($request){
    $table=ReportNewTables::find($request->table_name);
    $head=DB::connection(config('dynamic-extract.db_connection'))->table($table->table_name)->first();
    $arrrayData=collect($head)->toArray();
    $heading=array_keys($arrrayData);
    $tamanho=sizeof($heading);
    $date=false;
    $dateColumuns=false;

    $filtros=ReportNewSyncFiltro::where('groupo_filtro',$request->filtro)->get();
    foreach ($filtros as $key => $filtro) {
      $filtro_toV=ReportNewFiltro::find($filtro->filtro);
      if ($filtro_toV->type !="columuns") {
        foreach ($heading as $key_1 => $columun) {
          if ($filtro_toV->value==$columun) {
            $date=true;
          }
        }

      if ($date==false) {
        $msg='Filter: '.$filtro_toV->name.', Columun: '.$filtro_toV->value.' not compatible with Table: '.$table->name;
        return $msg;
      }
      if ($filtro_toV->type=="date") {
        $columun_type=DB::connection(config('dynamic-extract.db_connection'))->getSchemaBuilder()->getColumnType($table->table_name, $filtro_toV->value);
          if ($columun_type!="datetime") {
            $msg='Filter: '.$filtro_toV->name.', Columun: '.$filtro_toV->value.' is not DateTime and is not compatible with Table: '.$table->name;
            return $msg;
          }
      }
      $date=false;

      }
      if ($filtro_toV->type=="columuns") {
        $columuns_toV=ReportNewColumuns::where('report_new_filtro_id',$filtro_toV->id)->get();
        foreach ($columuns_toV as $key_2 => $columuns_to) {
          foreach ($heading as $key_3 => $head) {

              if ($columuns_to->name==$head) {
                $dateColumuns=true;
              }
          }
          if ($dateColumuns==false) {
            $msg='Filter: '.$filtro_toV->name.', Filter Columuns: '.$columuns_to->name.' not compatible with Table: '.$table->name;
            return $msg;
          }
          $dateColumuns=false;
        }

      }


    }
  }

  public function store_edit(Request $request)
  {
  	    $validatedData = $request->validate([
            'name'=>'required|string|max:70',
            'comments'=>'required|string|max:255',
            'can'=>'required|string',
            'filtro'=>'nullable|integer',
            'table_name'=>'required|integer',
            'user_id'=>'required|integer',
         ]);
  	   $data=ReportNew::find($request->id);
	  	if(!isset($data)){
	  		return back()->with('error','This report is no longer available');
	  	}
      $error=$this->validate_report($request);
      if(isset($error)){
        return back()->with('error',$error);
      }

  	    ReportNew::where('id',$request->id)
  	    			->update([
  	    				"name"=>$request->name,
                        "comments"=>$request->comments,
  	    				"can"=>$request->can,
  	    				"filtro"=>$request->filtro,
  	    				"table_name"=>$request->table_name,
  	    				"user_id"=>$request->user_id
  	    			]);

  	    return back()->with('success','You have edited report on list');

  }

  public function filtro_index()
  {
  	$data=ReportNewFiltroGroupo::get();
  	$filtros=ReportNewFiltro::with('lists','columuns')->orderby('id','desc')->get();
  	return view('extract-view::report.config.filtro_index',compact('data','filtros'));
  }
  public function filtro_index_store(Request $request)
  {
  	  	 $validatedData = $request->validate([
            'name'=>'required|string|max:70|unique:report_new_filtro_group',
            'filtros.*'=>'nullable|integer',
            'user_id'=>'required|integer',
         ]);

         $group=ReportNewFiltroGroupo::create([
         	'name'=>$request->name,
         	'user_id'=>$request->user_id
         ]);

         foreach ($request->filtros as $key => $value) {
         	ReportNewSyncFiltro::create([
         		'filtro'=>$value,
         		'groupo_filtro'=>$group->id
         		]);
         }

  	return back()->with('success','You have added group filter on the list');
  }
  public function delete_group_filter($id)
  {
  	  	$data=ReportNewFiltroGroupo::find($id);
	  	if(!isset($data)){
	  		return back()->with('error','This group is no longer available');
	  	}
        $check = ReportNew::where('filtro',$id)->first();
	  	if(isset($check)){
	  		return back()->with('error','This group is on using on report '.$check->name);
	  	}

        ReportNewSyncFiltro::where('groupo_filtro',$id)->delete();
        $data->delete();
        return redirect($this->prefix.'report/config/filtro')->with('success','Group Filter deleted successfully');
  }

  public function filtro_index_edit($id)
  {
  	  	$data=ReportNewFiltroGroupo::find($id);
	  	if(!isset($data)){
	  		return back()->with('error','This group is no longer available');
	  	}
  	$filtros=ReportNewFiltro::get();
  	$filtros_selected=ReportNewSyncFiltro::where('groupo_filtro',$id)->pluck('filtro')->all();
  	return view('extract-view::report.config.filtro_edit',compact('data','filtros','filtros_selected'));
  }

  public function filtro_index_edit_store(Request $request)
  {
  	  	 $validatedData = $request->validate([
            'name'=>'required|string|max:70',
            'filtros.*'=>'nullable|integer',
            'user_id'=>'required|integer',
         ]);
  	  	$data=ReportNewFiltroGroupo::find($request->id);
	  	if(!isset($data)){
	  		return back()->with('error','This group is no longer available');
	  	}

         $group=ReportNewFiltroGroupo::where('id',$request->id)
         ->update([
         	'name'=>$request->name,
         	'user_id'=>$request->user_id
         ]);
         ReportNewSyncFiltro::where('groupo_filtro',$request->id)->delete();
         if(isset($request->filtros)){
           foreach ($request->filtros as $key => $value) {
           	ReportNewSyncFiltro::create([
           		'filtro'=>$value,
           		'groupo_filtro'=>$request->id
           		]);
           }
        }

         return back()->with('success','You have edited group filter on the list');
  }
  public function filtros_all()
  {
  	$data=ReportNewFiltro::with('columuns','user','lists')->get();
  	return view('extract-view::report.config.filtros_all',compact('data'));
  }

  public function filtros_all_store(Request $request)
  {
  	  	$validatedData = $request->validate([
            'name'=>'required|string|max:70|unique:report_new_filtro',
            'value'=>'required|string|max:255',
            'type'=>'required|string|max:255',
            'user_id'=>'required|integer',
         ]);
  	  $id=ReportNewFiltro::create($request->except(['list_columuns']));

    if($request->list_columuns){


        foreach ($request->list_columuns as $key => $list) {
            $dataRequest=[
                'report_new_filtro_id'=>$id->id,
                'name'=>$list,
                'user_id'=>$request->user_id
            ];

            switch ($request->type) {
                case 'columuns':
                        $this->filtros_columuns_store_f($dataRequest);
                    break;
                    case 'list':
                        $this->filtros_list_store_f($dataRequest);
                    break;

            }
        }
    }

  	  return back()->with('success','You have added filter on the list');
  }

  public function filtros_all_edit($id)
  {

  	$data=ReportNewFiltro::with('lists','columuns')->find($id);
	if(!isset($data)){
  		return back()->with('error','This group is no longer available');
  	}
  	return view('extract-view::report.config.filtros_all_edit',compact('data'));
  }
  public function delete_filter($id)
  {
  	    $data=ReportNewFiltro::find($id);
  		if(!isset($data)){
	  		return back()->with('error','This filter is no longer available');
	  	}

        $check = ReportNewSyncFiltro::where('filtro',$id)->first();
        if(isset($check)){
            $group = ReportNewFiltroGroupo::where('id',$check->groupo_filtro)->first();
	  		return back()->with('error','This filter is using on group filters '.$group->name);
	  	}

        ReportNewColumuns::where('report_new_filtro_id',$id)->delete();
        ReportNewLists::where('report_new_filtro_id',$id)->delete();
        $data->delete();

         return redirect($this->prefix.'report/config/filtro/filtros')->with('success','Filter deleted successfully');
  }
  public function filtros_all_edit_store(Request $request)
  {
  	  	 $validatedData = $request->validate([
            'name'=>'required|string|max:70',
            'value'=>'required|string|max:255',
            'type'=>'required|string|max:255',
            'user_id'=>'required|integer',
         ]);

  	  ReportNewFiltro::where('id',$request->id)
  	  ->update($request->except('_token','list_columuns'));

        ReportNewColumuns::where('report_new_filtro_id',$request->id)->delete();
        ReportNewLists::where('report_new_filtro_id',$request->id)->delete();

        if($request->list_columuns){


            foreach ($request->list_columuns as $key => $list) {

                $dataRequest=[
                    'report_new_filtro_id'=>$request->id,
                    'name'=>$list,
                    'user_id'=>$request->user_id
                ];

                switch ($request->type) {
                    case 'columuns':
                            $this->filtros_columuns_store_f($dataRequest);
                        break;
                        case 'list':
                            $this->filtros_list_store_f($dataRequest);
                        break;

                }
            }
        }

  	  return redirect($this->prefix.'report/config/filtro/filtros')->with('success','You have edited filter on the list');
  }


  public function filtros_list()
  {
  	$data=ReportNewLists::get();
  	$filtros=ReportNewFiltro::where('type','list')->get();
  	return view('extract-view::report.config.filtros_list', compact('data','filtros'));
  }
  public function filtros_list_store(Request $request)
  {
         $request=[
            'report_new_filtro_id'=> $request->report_new_filtro_id,
            'name' => $request->name,
            'user_id' => $request->user_id
         ];
         $this->filtros_list_store_f($request);
  	return back()->with('success','You have added list to the list');
  }
  public function filtros_list_store_f($request)
  {
       $dataRequest=[
                'report_new_filtro_id'=>$request['report_new_filtro_id'],
                'name'=>$request['name'],
                'user_id'=>$request['user_id']
            ];
            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add($dataRequest);

  	  	 $validator = $myRequest->validate([
            'name'=>'required|string|max:70',
            'report_new_filtro_id'=>'required|integer',
            'user_id'=>'required|integer',
         ]);

         ReportNewLists::create($myRequest->all());
  }

  public function filtros_list_edit($id)
  {
  	$data=ReportNewLists::find($id);
  		if(!isset($data)){
	  		return back()->with('error','This group is no longer available');
	  	}
  	$filtros=ReportNewFiltro::where('type','list')->get();
  	return view('extract-view::report.config.filtros_list_edit', compact('data','filtros'));
  }

  public function filtros_list_edit_store(Request $request)
  {
  	  $data=ReportNewLists::find($request->id);
		if(!isset($data)){
	  		return back()->with('error','This group is no longer available');
	  	}
  	  	 $validatedData = $request->validate([
            'name'=>'required|string|max:70',
            'report_new_filtro_id'=>'required|integer',
            'user_id'=>'required|integer',
         ]);
  	  ReportNewLists::where('id',$request->id)
		->update($request->except('_token'));

  	return redirect($this->prefix.'report/config/filtro/list')->with('success','You have edited the list');
  }

  public function filtros_columuns()
  {
  	$data=ReportNewColumuns::get();
  	$filtros=ReportNewFiltro::where('type','columuns')->get();
  	return view('extract-view::report.config.filtros_columuns', compact('data','filtros'));

  }
  public function filtros_columuns_store(Request $request)
  {

         $request=[
            'report_new_filtro_id'=> $request->report_new_filtro_id,
            'name' => $request->name,
            'user_id' => $request->user_id
         ];

         $this->filtros_columuns_store_f($request);

  	return back()->with('success','You have added list to the list');
  }

  public function filtros_columuns_store_f($request)
  {

       $dataRequest=[
                'report_new_filtro_id'=>$request['report_new_filtro_id'],
                'name'=>$request['name'],
                'user_id'=>$request['user_id']
            ];
            $myRequest = new Request();
            $myRequest->setMethod('POST');
            $myRequest->request->add($dataRequest);

  	  	 $validator = $myRequest->validate([
            'name'=>'required|string|max:70',
            'report_new_filtro_id'=>'required|integer',
            'user_id'=>'required|integer',
         ]);
         ReportNewColumuns::create($myRequest->all());
  }

  public function filtros_columuns_edit($id)
  {
  	$data=ReportNewColumuns::find($id);
  		if(!isset($data)){
	  		return back()->with('error','This group is no longer available');
	  	}
  	$filtros=ReportNewFiltro::where('type','columuns')->get();

  	return view('extract-view::report.config.filtros_columuns_edit', compact('data','filtros'));
  }

  public function filtros_columuns_edit_store(Request $request)
  {
  	  $data=ReportNewColumuns::find($request->id);
		if(!isset($data)){
	  		return back()->with('error','This group is no longer available');
	  	}
	  	$validatedData = $request->validate([
            'name'=>'required|string|max:70',
            'report_new_filtro_id'=>'required|integer',
            'user_id'=>'required|integer',
         ]);

  	  ReportNewColumuns::where('id',$request->id)
  	  ->update($request->except('_token'));

  	return redirect($this->prefix.'report/config/filtro/columuns')->with('success','You have edited the list');
  }
}
