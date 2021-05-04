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
            'email' => 'required|email',
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

    public function listDependency($id)
    {
      $dependence = Dependence::find($id);
        if($dependence){
            return response($dependence);
        }
        return response(['message'=>'No existe la dependencia'], 404);
    }

    public function update(Request $request,$id)
    {
        $dependence = Dependence::where('id',$id)->first();

        if(!$dependence){
            return response()->json(['status'=>'404','data'=>'No existe dependencia']);
        }

        $validateDependence = Validator::make($request->all(),[
            'cost_center' => 'regex:((CO)(\d+)?$)',
            'description' => 'string',
            'email' => 'email',
            'status' => 'boolean'
        ]);

        if($validateDependence->fails()) {
            return response()
                ->json(['status'=>'500','data'=>$validateDependence->errors()]);
        }

        $request->cost_center ? $dependence->cost_center = $request->cost_center: false;
        $request->description ? $dependence->description = $request->description : false;
        $request->email ? $dependence->email = $request->email: false;
        $request->status ? $dependence->status = $request->status: false;
        $dependence->save();

        return response()
                    ->json(['status' => '200', 'data'=>$dependence,'message' => "Dependence Updated"]);
    }

    public function changeStatus($id) {
        $dependence = Dependence::where('id',$id)->first();

        if(!$dependence){
            return response()->json(['status'=>'404','data'=>'No existe la dependencia']);
        }

        $status = 0;
        if($dependence->status ==0) {
            $status = 1;
        }

        $dependence->status = $status;
        $dependence->save();

        return response()
                    ->json(['status' => '200','data'=>'Dependence Status Change','dependence_status'=>$status]);
    }
}
