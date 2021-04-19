<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prestamo;
use App\Models\Implemento;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageStatusLoanImplement;
use App\Mail\MessageStatusLoan;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Prestamo::join('Users','prestamos.id_user','=','users.id')
                           ->join('Implementos','prestamos.id_implemento','=','implementos.id')
                           ->select('prestamos.id','users.email','implementos.nombre as implemento',
                                    'prestamos.fecha_inicial','prestamos.fecha_final','prestamos.estado_prestamo',
                                    'prestamos.estado_implemento_prestamo','prestamos.cantidad_implemento_perdido','implementos.valor')
                           ->orderByRaw('prestamos.fecha_inicial desc')
                           ->get();
        return response($loans);  
    }

    public function listStatusLoans($status) {
        $loans = Prestamo::join('Users','prestamos.id_user','=','users.id')
                           ->join('Implementos','prestamos.id_implemento','=','implementos.id')
                           ->select('prestamos.id','users.email','implementos.nombre as implemento',
                                    'prestamos.fecha_inicial','prestamos.fecha_final','prestamos.estado_prestamo',
                                    'prestamos.estado_implemento_prestamo','prestamos.cantidad_implemento_perdido','implementos.valor')
                           ->where('prestamos.estado_prestamo','=',$status)  
                           ->orderByRaw('prestamos.fecha_inicial desc')                         
                           ->get();
        return response($loans);   
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
        $validateLoan = $request->validate([
            'id_implemento' => 'required|integer',
            'cantidad_implemento_solicitado' => 'required|integer',
            'fecha_inicial' => 'required',
            'fecha_final' => 'required'
        ]);

        $implement = Implemento::where('id','=',$request->id_implemento)->first();
        $cantidad_disponibles = $implement->cantidad - $implement->cantidad_prestados;

        if($request->cantidad_implemento_solicitado == 0) {
            return response(['message'=>"Cantidad de implementos solicitados no puede ser 0"]);
        }else if($request->cantidad_implemento_solicitado <= $cantidad_disponibles) {
            $prestamo = new Prestamo();
            $implement->cantidad_prestados += $request->cantidad_implemento_solicitado;
            $implement->save();
            $user = auth()->user();
            $prestamo->id_user = $user->id;
            $prestamo->id_implemento = $request->id_implemento;
            $prestamo->cantidad_implemento_solicitado = $request->cantidad_implemento_solicitado;
            $prestamo->fecha_inicial = $request->fecha_inicial;
            $prestamo->fecha_final = $request->fecha_final;
            $prestamo->save();
            return response($prestamo);
        } else {
            return response(['message'=>"Cantidad de implementos solicitados: {$request->cantidad_implemento_solicitado} es mayor a la cantidad disponible: {$cantidad_disponibles}"]);
        }
    }

    public function updateStatus($id,Request $request)
    {
        $changeStatus = $request->validate([
            'status' => 'required|string| in:Rechazado,Aprobado'
        ]);

        $status = $request->status;
        $loan = Prestamo::where('id','=',$id)->first();
        $loan_mail = Prestamo::join('Users','prestamos.id_user','=','users.id')
        ->join('Implementos','prestamos.id_implemento','=','implementos.id')
        ->where('prestamos.id','=',$id)
        ->select('prestamos.id','users.email','implementos.nombre as implemento','implementos.placa',
                 'prestamos.fecha_inicial','prestamos.fecha_final','prestamos.estado_prestamo',
                 'prestamos.estado_implemento_prestamo','prestamos.cantidad_implemento_solicitado','implementos.valor')
        ->get();


        if(!$loan) {
            return response(['message'=>"No existe prestamo"]);
        }
        
        Mail::to($loan_mail[0]->email)->send(new MessageStatusLoan($loan_mail,$status));
        $loan->estado_prestamo = $status;
        $loan->save();
        return response(['message'=>"Prestamo $id $status"]);
    }

    
    public function updateStatusImplement($id,Request $request)
    {
        $changeStatusImplement = $request->validate([
            'status_implement' => 'required|string| in:Entregado,Devuelto,Perdido,Defectuoso',
            'amount' => 'required|integer'
        ]);

        $loan = Prestamo::where('id','=',$id)->first();
        $status = $request->status_implement;
        $amount = $request->amount;

        if(!$loan) {
            return response(['message'=>"No existe prestamo"]);
        }

        if($amount>$loan->cantidad_implemento_solicitado){
            return response(['message'=>"Cantidad de implementos a estado {$status}: {$request->amount} es mayor a la cantidad solicitada por el usuario: {$loan->cantidad_implemento_solicitado}"]);
        }

        if($loan->cantidad_implemento_solicitado == $loan->cantidad_implemento_devuelto) {
            return response(['message'=>"No se deben devolver más implementos"]);
        }

        switch ($status) {
            case "Entregado":
                $loan->cantidad_implemento_entregado = $amount;
                break;
            case "Devuelto":
                $loan->cantidad_implemento_devuelto = $amount;
                $implement = Implemento::where('id','=',$loan->id_implemento)->first();
                $implement->cantidad_prestados -= $amount;
                $implement->save();
                break;
            case "Perdido":
                $loan->cantidad_implemento_perdido = $amount;
                break;
            case "Defectuoso";
                $loan->cantidad_implemento_defectuoso = $amount;
                break;
        };

        $loan->estado_implemento_prestamo = $status;
        $loan->save();

        $loan_mail = Prestamo::join('Users','prestamos.id_user','=','users.id')
        ->join('Implementos','prestamos.id_implemento','=','implementos.id')
        ->where('prestamos.id','=',$id)
        ->select('prestamos.id','users.email','implementos.nombre as implemento','implementos.placa',
                 'prestamos.fecha_inicial','prestamos.fecha_final','prestamos.estado_prestamo',
                 'prestamos.estado_implemento_prestamo','prestamos.cantidad_implemento_solicitado',
                 'prestamos.cantidad_implemento_entregado','prestamos.cantidad_implemento_devuelto',
                 'prestamos.cantidad_implemento_perdido','prestamos.cantidad_implemento_defectuoso','implementos.valor')
        ->get();
        
        Mail::to($loan_mail[0]->email)->send(new MessageStatusLoanImplement($loan_mail,$status));
        return response(['message'=>"Implemento del Prestamo $id $status"]);
    }

    public function listLoansUser()
    {
        $user = auth()->user();
        $id_user = $user->id;
        $loans = Prestamo::join('Users','prestamos.id_user','=','users.id')
                           ->join('Implementos','prestamos.id_implemento','=','implementos.id')
                           ->select('prestamos.id','users.email','implementos.nombre as implemento',
                                    'prestamos.fecha_inicial','prestamos.fecha_final','prestamos.estado_prestamo',
                                    'prestamos.estado_implemento_prestamo','implementos.valor',
                                    'prestamos.cantidad_implemento_solicitado','prestamos.cantidad_implemento_entregado',
                                    'prestamos.cantidad_implemento_devuelto','prestamos.cantidad_implemento_perdido',
                                    'prestamos.cantidad_implemento_defectuoso')
                           ->where('users.id','=',$id_user)
                           ->orderByRaw('prestamos.fecha_inicial desc')                         
                           ->get();
        return response($loans);
    }

    public function getLoanUser($id) 
    {
        $user = auth()->user();
        $id_user = $user->id;
        $loans = Prestamo::join('Users','prestamos.id_user','=','users.id')
                           ->join('Implementos','prestamos.id_implemento','=','implementos.id')
                           ->select('prestamos.id','users.email','implementos.nombre as implemento',
                                    'prestamos.fecha_inicial','prestamos.fecha_final','prestamos.estado_prestamo',
                                    'prestamos.estado_implemento_prestamo','implementos.valor',
                                    'prestamos.cantidad_implemento_solicitado','prestamos.cantidad_implemento_entregado',
                                    'prestamos.cantidad_implemento_devuelto','prestamos.cantidad_implemento_perdido',
                                    'prestamos.cantidad_implemento_defectuoso')
                           ->where('prestamos.id','=',$id)
                           ->orderByRaw('prestamos.fecha_inicial desc')                         
                           ->first();
        return response($loans);
    }

    public function listLoansDay(Request $request) 
    {
        $start = $request->start;
        $end = $request->end;

        $loans = Prestamo::join('Users','prestamos.id_user','=','users.id')
                            ->join('Implementos','prestamos.id_implemento','=','implementos.id')
                            ->select('prestamos.id','users.email','implementos.nombre as implemento',
                            'prestamos.fecha_inicial','prestamos.fecha_final','prestamos.estado_prestamo','prestamos.estado_implemento_prestamo',
                            'prestamos.cantidad_implemento_perdido','implementos.valor')
                            ->where('prestamos.estado_prestamo','=','Solicitado')
                            ->whereBetween('prestamos.fecha_inicial',[$start,$end]) 
                            ->orderByRaw('prestamos.fecha_inicial desc')                         
                            ->get();
        return response($loans);   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prestamo = Prestamo::where('id_implemento','=',$id)->get();
        
        if($prestamo) {
            return response($prestamo);
        }
        return response(['message'=>"No existe prestamo con id $id"]);
    }

    public function getLoanById($id)
    {
        $loan = Prestamo::join('Users','prestamos.id_user','=','users.id')
        ->join('Implementos','prestamos.id_implemento','=','implementos.id')
        ->where('prestamos.id','=',$id)
        ->select('prestamos.id','users.email','implementos.nombre as implemento','implementos.placa',
                 'prestamos.fecha_inicial','prestamos.fecha_final','prestamos.estado_prestamo',
                 'prestamos.estado_implemento_prestamo','prestamos.cantidad_implemento_solicitado',
                 'prestamos.cantidad_implemento_entregado','prestamos.cantidad_implemento_devuelto',
                 'prestamos.cantidad_implemento_perdido','prestamos.cantidad_implemento_defectuoso','implementos.valor')
                           ->where('prestamos.id','=',$id)
                           ->get();
        return response($loan);   
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
