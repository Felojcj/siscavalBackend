<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ValidValue;
use Illuminate\Support\Facades\Validator;

class ValidValueController extends Controller
{
    public function store(Request $request)
    {
        $validateValidValue = Validator::make($request->all(),[
            'value' => 'required|string',
            'id_detail' => 'required|integer',
            'status' => 'required|boolean'
        ]);

        if($validateValidValue->fails()) {
            return response()->json(['errors' => $validateValidValue->errors()], 500);
        }

        $validValue = ValidValue::create($validateValidValue->getData());

        return response()->json(['data' => $validValue], 201);
    }

    public function listValidValues()
    {
        return ValidValue::all();
    }

    public function listValidValue($id)
    {
        $validValue = ValidValue::find($id);

        if($validValue){
            return response($validValue);
        }

        return response(['message'=>'No existe el valor valido'], 404);
    }

    public function listValidDetailValue($id)
    {
        $validValue = ValidValue::where('id_detail', $id)->get();

        if(!$validValue->isEmpty()){
          return response($validValue);
        }

        return response(['status' => '404','message'=>'No existe el detalle']);
    }

    public function update(Request $request,$id)
    {
        $validValue = ValidValue::where('id',$id)->first();

        if(!$validValue){
            return response()->json(['status'=>'404','data'=>'No existe el valor valido']);
        }

        $validateValidValue = Validator::make($request->all(),[
            'value' => 'string',
            'id_detail' => 'integer',
            'status' => 'boolean'
        ]);

        if($validateValidValue->fails()) {
            return response()
                ->json(['status'=>'500','data'=>$validateValidValue->errors()]);
        }

        $request->value ? $validValue->value = $request->value: false;
        $request->id_detail ? $validValue->id_detail = $request->id_detail : false;
        $request->status ? $validValue->status = $request->status: false;
        $validValue->save();

        return response()
                    ->json(['status' => '200', 'data'=>$validValue,'message' => "Valor valido actualizado"]);
    }

    public function changeStatus($id) {
        $validValue = ValidValue::where('id',$id)->first();

        if(!$validValue){
            return response()->json(['status'=>'404','data'=>'No existe el valor valido']);
        }

        $status = 0;
        if($validValue->status ==0) {
            $status = 1;
        }

        $validValue->status = $status;
        $validValue->save();

        return response()
                    ->json(['status' => '200','data'=>'Estado del valor valido modificado','valid_value_status'=>$status]);
    }
}
