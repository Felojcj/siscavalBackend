<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Implemento;

class ImplementoController extends Controller
{
    
    public function index()
    {
        return Implemento::all();
    }

    public function create(Request $request)
    {
        $validateData = $request->validate([
            'nombre' => 'required|string',
            'placa' => 'required|string',
            'descripcion' => 'required|string',
            'valor' => 'required|string',
            'cantidad' => 'required| numeric |gt:0'
        ]);

        $implement = Implemento::create($validateData);
        return response(['message'=>$implement],201);
    }

    public function show($id)
    {
        $implement = Implemento::find($id);
        if($implement){
            return response($implement);
        }
        return response(['message'=>'No existe implemento'],404);
    }

    public function update(Request $request, $id)
    {
        $validateData = $request->validate([
            'nombre' => 'required|string',
            'placa' => 'required|string',
            'descripcion' => 'required|string',
            'valor' => 'required|string',
            'cantidad' => 'required| numeric |gt:0'
        ]);
        $implement = Implemento::find($id);
        if($implement) {
            $implement->nombre=$request->nombre;
            $implement->placa=$request->placa;
            $implement->descripcion=$request->descripcion;
            $implement->valor=$request->valor;
            $implement->cantidad=$request->cantidad;
            $implement->save();
            return response(['message'=>'Implemento Actualizado'],200);
        }
        return response(['message'=>'No existe implemento'],404);
    }

    public function destroy($id) {
        $implement = Implemento::find($id);
        if($implement) {
            $implement->delete();
            return response(['message'=>'Escenario Eliminado'],200);
        }
        return response(['message'=>'No existe implemento'],404);
    }
}
