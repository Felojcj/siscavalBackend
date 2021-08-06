<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnrolledByGender;
use App\Imports\EnrolledByGendersImport;
use App\Exports\EnrolledByGendersExport;
use Illuminate\Support\Facades\Validator;
use Excel;

class EnrolledByGenderController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'import_file' => 'required|mimes:xlsx,xlx,xls'
        ]);

        if($validate->fails()) {
            return response()->json(['status'=>'500','data'=>$validate->errors()]);
        }

        Excel::import(new EnrolledByGendersImport, request()->file('import_file'));

        return response()->json(['status' => '201', 'data' => 'Ok']);
    }

    public function listEnrolledByGender()
    {
        return EnrolledByGender::filter()->get();
    }

    public function export() 
    {
        return Excel::download(new EnrolledByGendersExport, 'matriculados por genero.xlsx');
    }
}
