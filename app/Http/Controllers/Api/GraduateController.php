<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Graduate;
use App\Imports\GraduatesImport;
use Illuminate\Support\Facades\Validator;
use Excel;

class GraduateController extends Controller
{
    public function store(Request $request)
    {
        $validateSchedule = Validator::make($request->all(),[
            'import_file' => 'required|mimes:xlsx,xlx,xls'
        ]);

        Excel::import(new GraduatesImport, request()->file('import_file'));

        return response()->json(['status' => '201', 'data' => 'Ok']);
    }

    public function listGraduates()
    {
        return Graduate::all();
    }
}