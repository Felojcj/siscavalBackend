<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Escenario;

class EscenarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Escenario::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validateData = $request->validate([
            'nombre' => 'required | string',
            'codigo' => 'required | string',
            'descripcion' => 'required | string',
            'imagen' => 'required',
            'medidas' => 'required | string'
        ]);

        $scenario = Escenario::create($validateData);
        return response(['message'=>$scenario],201);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $scenario = Escenario::find($id);
        if($scenario){
            return response($scenario);
        }
        return response(['message'=>'No existe escenario'],404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validateData = $request -> validate([
            'nombre' => 'string',
            'codigo' => 'string',
            'imagen' => 'string',
            'descripcion' => 'string',
            'medidas' => 'string'
        ]);
        $scenario = Escenario::find($id);
        if($scenario){
            $scenario->nombre=$request->nombre;
            $scenario->codigo=$request->codigo;
            $scenario->imagen=$request->imagen;
            $scenario->descripcion=$request->descripcion;
            $scenario->medidas=$request->medidas;
            $scenario->save();
            return response(['message'=>'Escenario Actualizado'],200);
        }
        return response(['message'=>'No existe escenario'],404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $scenario = Escenario::find($id);
        if($scenario) {
            $scenario->delete();
            return response(['message'=>'Escenario Eliminado'],200);
        }
        return response(['message'=>'No existe escenario'],404);
    }
}
