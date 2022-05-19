<?php

namespace Enhacudima\DynamicExtract\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Enhacudima\DynamicExtract\DataBase\Model\ProcessedFiles;
use Enhacudima\DynamicExtract\DataBase\Model\ReportNew;
use DB;
use Enhacudima\DynamicExtract\Http\Controllers\ExportQueryController;

class RelatorioExport implements FromQuery, ShouldAutoSize, WithHeadings, WithEvents
{

    use Exportable,RegistersEventListeners;
    /**
    * @return \Illuminate\Support\Collection
    */
    public static $filename;

    public function __construct($filename,$start,$end,$type,$filtro,$request,$user_id)
    {
        RelatorioExport::$filename = $filename;
        $this->q = new ExportQueryController($start,$end,$type,$filtro,$request,$user_id);
    }
    public function headings(): array
    {
        $eq=$this->q;
        $eq = $eq->query();
        return $eq['heading'];
    }

    public function query()
    {
        $eq=$this->q;
        $eq = $eq->query();
        return $eq['data'];
    }

    public static function afterSheet(AfterSheet $event)
    {
        ProcessedFiles::where('filename',self::$filename)
        ->update([
            'status' => 0,
        ]);
    }
}
