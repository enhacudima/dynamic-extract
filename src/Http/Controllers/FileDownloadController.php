<?php

namespace Enhacudima\DynamicExtract\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Storage;
use Enhacudima\DynamicExtract\DataBase\Model\ProcessedFiles;

class FileDownloadController extends Controller
{

    public function index($filename) {
    	$data=ProcessedFiles::where('filename',$filename)->first();

        $file = $data->path.$filename;
        return Storage::download('public/'.$file);
    }
}
