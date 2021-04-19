<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dependence;
use Illuminate\Support\Facades\Validator;

class DependenceController extends Controller
{
    public function store(Request $request)
    {
        $validateDependence = Validator::make($request->all(),[
            'cost_center' => 'required|regex:((CO)(\d+)?$)|unique:dependences',
            'description' => 'required|string',
            'email' => 'required|email|unique:dependences',
            'status' => 'required|boolean'
        ]);

        if($validateDependence->fails()) {
            return response()
                ->json(['status'=>'500','data'=>$validateDependence->errors()]);
        }

        $dependence = Dependence::create($validateDependence->getData());

        return response()
                    ->json(['status' => '201', 'data' => $dependence]);
    }

    public function listDependences()
    {
        return Dependence::all();
    }

    public function update(Request $request,$id)
    {
        $dependence = Dependence::where('id',$id)->update([
            'cost_center' => $request->cost_center,
            'description' => $request->description,
            'email' => $request->email,
            'status' => $request->status
        ]);

        return response()
                    ->json(['status' => '200', 'data' => "Dependence Updated"]);
    }

    public function changeStatus($id) {
        $dependence = Dependence::where('id',$id)->first();
        $status = 0;
        if($dependence->status ==0) {
            $status = 1;
        }

        $dependence->status = $status;
        $dependence->save();

        return response()
                    ->json(['status' => '200','data'=>'Dependence Status Change']);
    }
}
