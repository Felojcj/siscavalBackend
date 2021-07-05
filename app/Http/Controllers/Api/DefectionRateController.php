<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DefectionRate;
use App\Imports\DefectionRatesImport;
use App\Exports\DefectionRatesExport;
use Illuminate\Support\Facades\Validator;
use Excel;

class DefectionRateController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'import_file' => 'required|mimes:xlsx,xlx,xls'
        ]);

        if($validate->fails()) {
            return response()->json(['status'=>'500','data'=>$validate->errors()]);
        }

        Excel::import(new DefectionRatesImport, request()->file('import_file'));

        return response()->json(['status' => '201', 'data' => 'Ok']);
    }

    public function listDefectionRates()
    {
        return DefectionRate::all();
    }

    public function export() 
    {
        return Excel::download(new DefectionRatesExport, 'tasa de desercion.xlsx');
    }
}
