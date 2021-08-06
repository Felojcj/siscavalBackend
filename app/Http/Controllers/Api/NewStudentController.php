<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewStudent;
use App\Imports\NewStudentsImport;
use App\Exports\NewStudentsExport;
use Illuminate\Support\Facades\Validator;
use Excel;

class NewStudentController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'import_file' => 'required|mimes:xlsx,xlx,xls'
        ]);

        if($validate->fails()) {
            return response()->json(['status'=>'500','data'=>$validate->errors()]);
        }

        Excel::import(new NewStudentsImport, request()->file('import_file'));

        return response()->json(['status' => '201', 'data' => 'Ok']);
    }

    public function listNewStudents()
    {
        return NewStudent::filter()->get();
    }

    public function export() 
    {
        return Excel::download(new NewStudentsExport, 'inscritos, admitidos y nuevos.xlsx');
    }
}