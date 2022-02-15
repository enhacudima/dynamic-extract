<?php

namespace Enhacudima\DynamicExtract\Http\Controllers\Reports;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Enhacudima\DynamicExtract\DataBase\Model\ProcessedFiles;
use Enhacudima\DynamicExtract\Jobs\Notifications\NotifyUserOfCompletedExport;
use Enhacudima\DynamicExtract\Exports\RelatorioExport;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNew;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use File;
use Storage;
use Enhacudima\DynamicExtract\Http\Controllers\ExportQueryController;
use Illuminate\Support\Facades\Cookie;


class ExtractControllerReport extends Controller
{

        public function __construct()
    {
        $this->prefix = config('dynamic-extract.prefix');

        if(config('dynamic-extract.auth')){
            $this->middleware('auth');
            if(config('dynamic-extract.middleware.permission.active')){
                $this->middleware('permission:'.config('dynamic-extract.middleware.extract'));
            }
        }else{
            $this->middleware(function ($request, $next) {
                $value = $request->cookie('access_user_token');
                $storage = Cookie::get('access_user_token');
                if(!$value or $value != $storage ){
                    return redirect($this->prefix.'/');
                }
                return $next($request);
            });
        }


    }


  public function index()
  {

      $data=ProcessedFiles::with('user')->orderby('processed_files.created_at','desc')
        ->get();

      return view('extract-view::report.index',compact('data'));
  }

      public function deletefile($file)
    {
        $data=ProcessedFiles::where('filename',$file)->first();
        Storage::delete($data->path.$data->filename);
        $data->delete();

        return back()->with('success','Deleted successfully');
    }

    public function alldeletefile()
    {
            $files=ProcessedFiles::get();

            foreach ($files as $file)
            {
              Storage::delete($file->path.$file->filename);
            }

            DB::delete('delete from processed_files');
        return back()->with('success','All deleted successfully');
    }


    public function new ()
    {
        $data=ReportNew::where('status',1)->orderby('name','asc')->paginate(18);
        return view('extract-view::report.new', compact('data'));
    }



  public function open_report_extract($id,$type){
    $report=ReportNew::where('id',$id)->where('status',1)->first();
    if(!isset($report)){
        return back()->with('error','This report is no longer available');
    }
    switch ($type) {
        case 'table':
            $process_url ='/report/filtro/table';
            $process_icon ='<i class="far fa-eye"></i>';
            break;

        default:
            $process_url ='/report/filtro';
            $process_icon ='<i class="far fa-file-excel"></i>';
            break;
    }

    return view('extract-view::report.genarete',compact('report','process_url','process_icon'))->with('success','Select your filters to continue');

  }

    public function view_filtro(Request $request){
        $end=date('Y-m-d');
        $start=date('Y-m-01');

        if (isset($request->start)) {
              $request->validate([
                  'start'=>'required|date',
              ]);
              $start=Carbon::parse($request->start);
        }
        if (isset($request->end)) {
              $request->validate([
                  'end'=>'required|date'
              ]);
          $end=Carbon::parse($request->end)->endOfDay();
        }

          $type=$request->type;
          $filtro=$request->filtro;
        $q = new ExportQueryController($start,$end,$type,$filtro,$request->all());
        $eq=$q->query();
        $heading = $eq['heading'];
        $data = $eq['data'];
        $data=$data->take(500)->get();
        #dd($data);
        return view('extract-view::report.tableRows',compact('heading','data'))->with('warning','Max 500 rows');

    }
    public function filtro(Request $request)
    {
        $end=date('Y-m-d');
        $start=date('Y-m-01');

        if (isset($request->start)) {
              $request->validate([
                  'start'=>'required|date',
              ]);
              $start=Carbon::parse($request->start);
        }
        if (isset($request->end)) {
              $request->validate([
                  'end'=>'required|date'
              ]);
          $end=Carbon::parse($request->end)->endOfDay();
        }

          $type=$request->type;
          $filtro=$request->filtro;
        $new_str = str_replace(' ', '', $request->report_name);
        $filename=$new_str.'_'.time().'.xlsx';
        $filterData = $request->except(['_token','can','report_id']);

        $path = config('dynamic-extract.prefix').'/'.$filename;
        $data=[];
        $data['filename']=$filename;
        $data['path']=config('dynamic-extract.prefix').'/';
        $data['user_id']=Auth::user()->id ?? 0;
        $data['can']=$request->can;
        $data['filterData']=$filterData;
        $processed=ProcessedFiles::create($data);

        if(!config('dynamic-extract.queue')){
            try{
                Excel::store(new RelatorioExport($filename,$start,$end,$type,$filtro,$request->all()), $path);
            }catch (Throwable $e) {
                return back()->with('error','Error: '.$e->getMessage());
            }
        }else{
            try{

                if(config('dynamic-extract.auth')){
                    (new RelatorioExport($filename,$start,$end,$type,$filtro,$request->all()))->queue($path)->chain([
                        new NotifyUserOfCompletedExport(request()->user(),$filename),
                    ]);
                }else{
                    (new RelatorioExport($filename,$start,$end,$type,$filtro,$request->all()))->queue($path);
                }

            }catch (Exception $e) {
                return back()->with('error','Error: '.$e->getMessage());
            }
        }



        return Redirect(url($this->prefix.'/report/index'))->withSuccess('Export starting..');

    }



}
