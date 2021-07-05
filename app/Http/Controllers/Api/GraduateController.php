<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Graduate;
use App\Imports\GraduatesImport;
use App\Exports\GraduatesExport;
use Illuminate\Support\Facades\Validator;
use Excel;

class GraduateController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'import_file' => 'required|mimes:xlsx,xlx,xls'
        ]);

        if($validate->fails()) {
            return response()->json(['status'=>'500','data'=>$validate->errors()]);
        }

        Excel::import(new GraduatesImport, request()->file('import_file'));

        return response()->json(['status' => '201', 'data' => 'Ok']);
    }

    public function listGraduates()
    {
        return Graduate::all();
    }

    public function export() 
    {
        return Excel::download(new GraduatesExport, 'graduados.xlsx');
    }
}