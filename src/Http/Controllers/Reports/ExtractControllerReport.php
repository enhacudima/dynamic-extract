<?php

namespace Enhacudima\DynamicExtract\Http\Controllers\Reports;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Enhacudima\DynamicExtract\DataBase\Model\ProcessedFiles;
use Enhacudima\DynamicExtract\Jobs\Notifications\NotifyUserOfCompletedExport;
use Enhacudima\DynamicExtract\Exports\RelatorioExport;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNew;
use Enhacudima\DynamicExtract\DataBase\Model\ReportFavorites;
use DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use File;
use Storage;
use Enhacudima\DynamicExtract\Http\Controllers\ExportQueryController;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Collection;


class ExtractControllerReport extends Controller
{

    public $user_id;
    public $user_model;
        public function __construct()
    {
        $this->prefix = config('dynamic-extract.prefix');

        if(config('dynamic-extract.auth')){
            $this->middleware('auth');

            if(config('dynamic-extract.middleware.permission.active')){
                $this->middleware('can:'.config('dynamic-extract.middleware.extract'));
            }
            $this->user_model = config('dynamic-extract.middleware.model');

        }else{
            $this->middleware(function ($request, $next) {
                $value = $request->cookie('access_user_token');
                $storage = Cookie::get('access_user_token');
                if(!$value or $value != $storage ){
                    return redirect($this->prefix.'/');
                }
                $user = Cookie::get('access_user');
                $user = json_decode($user);
                $this->user_id = $user->id;
                $this->user_model = Enhacudima\DynamicExtract\DataBase\Model\Access::class;

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

        $user_id =$this->user_inf();
        $data_in=ReportNew::with(['favorite' => function($query) use ($user_id)
            {
                $query->where('report_new_favorites.user_id', $user_id);

            }])
            ->where('status',1)->orderby('name','asc')->orderby('name')->get();
            //dd($data_in);
        $data = $this->convert($data_in)->paginate(12);
        //dd($data);

        $data_favorite_in=ReportFavorites::with('report')->orderby('updated_at','desc')->where('user_id',$user_id)->get();
        $data_favorite = $this->convert($data_favorite_in);

        return view('extract-view::report.new', compact('data','data_favorite'));
    }

    public function convert($data){
        $temp =new Collection();
        foreach ($data as $key => $report) {
            if(config('dynamic-extract.auth') ? Auth::user()->can($report->can) : true){
                //array_push($temp,$report);
                $temp->push((object) $report);
            }
        }

        return $temp;
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
            $process_icon ='<i class="fas fa-download"></i>';
            break;
    }

    return view('extract-view::report.genarete',compact('report','process_url','process_icon'))->with('success','Select your filters to continue');

  }

    public function view_filtro(Request $request){
        $end=date('Y-m-d');
        $end=Carbon::parse($end)->endOfDay();
        $start=date('Y-m-01');
        $start=Carbon::parse($start)->startOfDay();

        if (isset($request->start)) {
              $request->validate([
                  'start'=>'required|date',
              ]);
            $start=Carbon::parse($request->start)->startOfDay();
        }
        if (isset($request->end)) {
              $request->validate([
                  'end'=>'required|date'
              ]);
            $end=Carbon::parse($request->end)->endOfDay();
        }

          $type=$request->type;
          $filtro=$request->filtro;
        $q = new ExportQueryController($start,$end,$type,$filtro,$request->all(),$this->user_inf());
        $eq=$q->query();
        $heading = $eq['heading'];
        $data = $eq['data'];
        $data = $data->take(config('dynamic-extract.preview_limit'))->get();
        $report = $request->report_name;
        return view('extract-view::report.tableRows',compact('report','heading','data'));

    }
    public function filtro(Request $request)
    {
        $end=date('Y-m-d');
        $end=Carbon::parse($end)->endOfDay();
        $start=date('Y-m-01');
        $start=Carbon::parse($start)->startOfDay();

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
        //determine file name and format
        if(isset($request->file_format))
            switch ($request->file_format) {
                case 'csv':
                    $filename=$new_str.'_'.time().'.csv';
                    break;
                default:
                    $filename=$new_str.'_'.time().'.xlsx';
                    break;
            }
        else
        $filename=$new_str.'_'.time().'.xlsx';

        $filterData = $request->except(['_token','can','report_id']);

        $path = 'public/'.config('dynamic-extract.prefix').'/'.$filename;
        $data=[];
        $data['filename']=$filename;
        $data['path']=config('dynamic-extract.prefix').'/';
        $data['user_id']=$this->user_inf();
        $data['can']=$request->can;
        $data['filterData']=$filterData;
        $processed=ProcessedFiles::create($data);

        if(!config('dynamic-extract.queue')){
            try{
                Excel::store(new RelatorioExport($filename,$start,$end,$type,$filtro,$request->all(),$this->user_inf()), $path);
            }catch (Throwable $e) {
                return back()->with('error','Error: '.$e->getMessage());
            }
        }else{
            try{

                if(config('dynamic-extract.auth')){
                    (new RelatorioExport($filename,$start,$end,$type,$filtro,$request->all(),$this->user_inf()))->queue($path)->chain([
                        new NotifyUserOfCompletedExport(request()->user(),$filename),
                    ]);
                }else{
                    (new RelatorioExport($filename,$start,$end,$type,$filtro,$request->all(),$this->user_inf()))->queue($path);
                }

            }catch (Exception $e) {
                return back()->with('error','Error: '.$e->getMessage());
            }
        }



        return Redirect(url($this->prefix.'/report/index'))->withSuccess('Export starting..');

    }


  public function favorite($id)
  {
    $report=ReportNew::where('id',$id)->where('status',1)->first();
    if(!isset($report)){
        return back()->with('error','This report is no longer available');
    };
    ReportFavorites::Favorite($this->user_inf(), $this->user_model, $id);
    return back()->with('success',"{$report->name} added to favorites");

  }

  public function favorite_remove($id)
  {
    ReportFavorites::where('id',$id)->delete();
    return back()->with('success',"Removed from favorites");

  }

  public function search_reports(Request $request)
  {
        $user_id = $this->user_inf();

        $data_in=ReportNew::with(['favorite' => function($query) use ($user_id)
            {
                $query->where('report_new_favorites.user_id', $user_id);

            }])
            ->where('name', 'like', "%{$request->search}%")
            ->where('status',1)->orderby('name','asc')
            ->orderby('name')
            ->get();

        $data = $this->convert($data_in)->paginate(16);

        return view('extract-view::report.search', compact('data'));

  }

  public function user_inf()
  {

        if(Auth::check()){
            $user_id = Auth::id();
        }else{
            $user_id = $this->user_id;
        }

        return $user_id;
  }



}
