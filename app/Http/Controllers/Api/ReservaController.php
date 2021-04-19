<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageStatus;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservas = Reserva::join('Users','reservas.id_user','=','users.id')
                           ->join('Escenarios','reservas.id_escenario','=','escenarios.id')
                           ->select('reservas.id','users.email','escenarios.nombre as escenario',
                                    'reservas.fecha_inicial','reservas.fecha_final','reservas.estado')
                           ->orderByRaw('reservas.fecha_inicial desc')
                           ->get();
        return response($reservas);   
    }

    public function listStatusReservations($status) {
        $reservas = Reserva::join('Users','reservas.id_user','=','users.id')
                           ->join('Escenarios','reservas.id_escenario','=','escenarios.id')
                           ->select('reservas.id','users.email','escenarios.nombre as escenario',
                                    'reservas.fecha_inicial','reservas.fecha_final','reservas.estado')
                           ->where('reservas.estado','=',$status)  
                           ->orderByRaw('reservas.fecha_inicial desc')                         
                           ->get();
        return response($reservas);   
    }

    public function listReservationsDay(Request $request) 
    {
        $start = $request->start;
        $end = $request->end;

        $reservas = Reserva::join('Users','reservas.id_user','=','users.id')
                            ->join('Escenarios','reservas.id_escenario','=','escenarios.id')
                            ->select('reservas.id','users.email','escenarios.nombre as escenario',
                            'reservas.fecha_inicial','reservas.fecha_final','reservas.estado')
                            ->where('reservas.estado','=','Solicitado')
                            ->whereBetween('reservas.fecha_inicial',[$start,$end]) 
                            ->orderByRaw('reservas.fecha_inicial desc')                         
                            ->get();
        return response($reservas);   
    }

    public function updateStatus($id,Request $request)
    {
        $changeStatus = $request->validate([
            'status' => 'required|string| in:Rechazado,Aprobado'
        ]);

        $status = $request->status;
        $reservation = Reserva::where('id','=',$id)->first();
        $reservation_mail = Reserva::join('Users','reservas.id_user','=','users.id')
        ->join('Escenarios','reservas.id_escenario','=','escenarios.id')
        ->where('reservas.id','=',$id)
        ->select('reservas.id','users.email','escenarios.nombre as escenario','escenarios.codigo',
                 'reservas.fecha_inicial','reservas.fecha_final','reservas.estado')
        ->get();

        if(!$reservation) {
            return response(['message'=>"No existe reserva"]);
        }
        
        Mail::to($reservation_mail[0]->email)->send(new MessageStatus($reservation_mail,$status));
        $reservation->estado = $status;
        $reservation->save();
        return response(['message'=>"Reserva $id $status"]);
    }

    public function listReservationsUser(){
        $user = auth()->user();
        $id_user = $user->id;
        $reservas = Reserva::join('Users','reservas.id_user','=','users.id')
                           ->join('Escenarios','reservas.id_escenario','=','escenarios.id')
                           ->select('reservas.id','users.email','escenarios.nombre as escenario',
                                    'reservas.fecha_inicial','reservas.fecha_final','reservas.estado')
                           ->where('users.id','=',$id_user)
                           ->orderByRaw('reservas.fecha_inicial desc')                         
                           ->get();
        return response($reservas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateReservation = $request->validate([
            'id_escenario' => 'required|integer',
            'fecha_inicial' => 'required',
            'fecha_final' => 'required'
        ]);

        $reserva = new Reserva();
        $user = auth()->user();
        $reserva->id_user = $user->id;
        $reserva->id_escenario = $request->id_escenario;
        $reserva->fecha_inicial = $request->fecha_inicial;
        $reserva->fecha_final = $request->fecha_final;
        $reserva->save();
        return response($reserva);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reserva = Reserva::where('id_escenario','=',$id)->get();
        
        if($reserva) {
            return response($reserva);
        }
        return response(['message'=>"No existe reserva con id $id"]);
    }

    public function getReservationById($id)
    {
        $reserva = Reserva::join('Users','reservas.id_user','=','users.id')
                           ->join('Escenarios','reservas.id_escenario','=','escenarios.id')
                           ->select('reservas.id','users.email','escenarios.nombre as escenario',
                                    'reservas.fecha_inicial','reservas.fecha_final','reservas.estado')
                           ->where('reservas.id','=',$id)
                           ->get();
        return response($reserva);      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
