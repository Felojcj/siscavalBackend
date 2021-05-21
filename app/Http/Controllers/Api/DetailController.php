<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Detail;
use Illuminate\Support\Facades\Validator;

class DetailController extends Controller
{
    public function store(Request $request)
    {
        $validateDetail = Validator::make($request->all(),[
            'column_name' => 'required|string|unique:details',
            'data_type' => 'required|string',
            // 'min_length' => 'required|integer|min:0|not_in:0',
            // 'max_length' => "required|integer|min:{$request->min_length}|not_in:{$request->min_length}",
            'id_template' => 'required|integer',
            // 'valid_value' => 'required|boolean',
            'status' => 'required|boolean'
        ]);

        if($validateDetail->fails()) {
            return response()->json(['status' => '500','data' => $validateDetail->errors()]);
        }

        $detail = Detail::create($validateDetail->getData());

        return response()->json(['status' => '201', 'data' => $detail], 201);
    }

    public function listDetails()
    {
        return Detail::paginate(10);
    }

    public function listDetail($id)
    {
        $detail = Detail::where('id_template', $id)->get();

        if(!$detail->isEmpty()){
            return response($detail);
        }

        return response(['status' => '404','message'=>'No existe el detalle']);
    }

    public function update(Request $request, $id)
    {
        $detail = Detail::where('id', $id)->first();

        if(!$detail){
            return response()->json(['status'=>'404','data'=>'No existe el detalle']);
        }

        $validateDetail = Validator::make($request->all(),[
            'column_name' => 'string',
            'data_type' => 'string',
            // 'min_length' => 'integer',
            // 'max_length' => 'integer',
            'id_template' => 'integer',
            // 'valid_value' => 'boolean',
            'status' => 'boolean'
        ]);

        if($validateDetail->fails()) {
            return response()
                ->json(['status'=>'500','data'=>$validateDetail->errors()], 500);
        }

        $request->column_name ? $detail->column_name = $request->column_name: false;
        $request->data_type ? $detail->data_type = $request->data_type : false;
        // $request->min_length ? $detail->min_length = $request->min_length: false;
        // $request->max_length ? $detail->max_length = $request->max_length: false;
        $request->id_template ? $detail->id_template = $request->id_template: false;
        // $request->valid_value ? $detail->valid_value = $request->valid_value: false;
        $request->status ? $detail->status = $request->status: false;
        $detail->save();

        return response()
                    ->json(['status' => '200', 'data'=>$detail,'message' => "Detalle Actualizado"], 200);
    }

    public function changeStatus($id) {
        $detail = Detail::where('id', $id)->first();

        if(!$detail){
            return response()->json(['status'=>'404','data'=>'No existe el detalle'], 404);
        }

        $status = 0;
        if($detail->status ==0) {
            $status = 1;
        }

        $detail->status = $status;
        $detail->save();

        return response()
                    ->json(['status' => '200','data'=>'El estado de el detalle cambio ','detail_status'=>$status], 200);
    }
}
