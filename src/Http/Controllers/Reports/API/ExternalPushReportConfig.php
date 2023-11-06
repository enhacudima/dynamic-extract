<?php

namespace Enhacudima\DynamicExtract\Http\Controllers\Reports\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Enhacudima\DynamicExtract\DataBase\Model\Access;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNew;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewApiExternalPushData;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewApiExternalSchedule;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewFiltroGroupo;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNewTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExternalPushReportConfig extends Controller
{

    public $user_id;
    public $user_model;
    public $prefix;
    public function __construct()
    {
        $this->prefix = config('dynamic-extract.prefix');

        if (config('dynamic-extract.auth')) {
            $this->middleware('auth');

            if (config('dynamic-extract.middleware.permission.active')) {
                $this->middleware('can:' . config('dynamic-extract.middleware.extract'));
            }
            $this->user_model = config('dynamic-extract.middleware.model');

        } else {
            $this->middleware(function ($request, $next) {
                $value = $request->cookie('access_user_token');
                $storage = Cookie::get('access_user_token');
                if (!$value or $value != $storage) {
                    return redirect($this->prefix . '/');
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
        $filtros = ReportNewFiltroGroupo::get();
        $tables = ReportNewTables::get();
        $data = ReportNewApiExternalPushData::query()
            ->with('table')
            ->get();

        return view('extract-view::report.config.api.index', compact('data', 'filtros', 'tables'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:70',
            'advance_query' => 'required|integer',
            'table_name' => 'required_if:advance_query,==,0',
            'text_query' => 'required_if:advance_query,==,1',
            'expire_at' => 'nullable|date',
            'paginate' => 'nullable',
            'user_id' => 'required|integer',
        ],
        [
            'table_name.required_if'=> 'The text query field is required when advance query is true.',
            'text_query.required_if' => 'The table name field is required when advance query is false.'
        ]
    );
        $validatedData['access_link'] =  (string) Str::uuid();
        ReportNewApiExternalPushData::create($validatedData);

        return back()->with('success', 'You have add new api report on list');

    }


    public function store_edit(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:70',
                'advance_query' => 'required|integer',
                'table_name' => 'required_if:advance_query,==,0',
                'text_query' => 'required_if:advance_query,==,1',
                'expire_at' => 'nullable|date',
                'paginate' => 'nullable',
                'user_id' => 'required|integer',
            ],
            [
                'table_name.required_if' => 'The text query field is required when advance query is true.',
                'text_query.required_if' => 'The table name field is required when advance query is false.'
            ]
        );
        $data = ReportNewApiExternalPushData::find($request->id);
        if (!isset($data)) {
            return back()->with('error', 'This report is no longer available');
        }
        ReportNewApiExternalPushData::where('id', $request->id)
            ->update($validatedData);

        return back()->with('success', 'You have edited report on list');

    }
    public function delete($id)
    {
        $data = ReportNewApiExternalPushData::find($id);
        $status = 0;
        if ($data->status == 0) {
            $status = 1;
        }

        ReportNewApiExternalPushData::where('id', $id)
            ->update(['status' => $status]);

        return back()->with('success', 'You changed report on the list');
    }

    public function delete_report($id)
    {
        $data = ReportNewApiExternalPushData::find($id);
        if (!isset($data)) {
            return back()->with('error', 'This report is no longer available');
        }
        $data->delete();
        return redirect($this->prefix . 'report/config/external/api')->with('success', 'Report deleted successfully');
    }
    public function edit($id)
    {
        $data = ReportNewApiExternalPushData::find($id);
        if (!isset($data)) {
            return back();
        }
        $filtros = ReportNewFiltroGroupo::get();
        $tables = ReportNewTables::get();

        return view('extract-view::report.config.api.edit', compact('data', 'filtros', 'tables'));

    }

    public function schedule($id)
    {
        $data = ReportNewApiExternalPushData::find($id);
        if (!isset($data)) {
            return back();
        }
        $filtros = ReportNewFiltroGroupo::get();
        $tables = ReportNewTables::get();
        $schedule = ReportNewApiExternalSchedule::where('report_id', $id)
            ->orderby('time_end', 'asc')->get();

        return view('extract-view::report.config.api.schedule', compact('data', 'filtros', 'tables', 'id','schedule'));

    }

    public function schedule_add(Request $request)
    {
        $validatedData = $request->validate(
            [
                'report_id' => 'required|exists:report_new_api_external_push_data,id',
                'method' => ['nullable', Rule::in(['Allow', 'Deny'])],
                'time_start' => 'required|date_format:H:i',
                'time_end' => 'required|date_format:H:i|after:time_start',
                'user_id' => 'required|integer',
            ],
            [

            ]
        );
        $data = ReportNewApiExternalPushData::find($request->report_id);
        if (!isset($data)) {
            return back()->with('error', 'This report is no longer available');
        }
        ReportNewApiExternalSchedule::updateOrCreate(
            [
                'method' => $request->method,
                'time_start' => $request->time_start,
                'time_end' => $request->time_end,
            ],
            $validatedData
        );

        return back()->with('success', 'You have edited report on list');

    }

    public function schedule_remove($id)
    {
        $data = ReportNewApiExternalSchedule::find($id);
        if (!isset($data)) {
            return back()->with('error', 'This item is no longer available');
        }
        $data->delete();
        return back()->with('success', 'Report deleted successfully');
    }


}
