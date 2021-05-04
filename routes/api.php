<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\ImplementoController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\EscenarioController;
use App\Http\Controllers\Api\PrestamoController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\DependenceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login'])->middleware('exist','actived','verification');

Route::post('/password/email',[ForgotPasswordController::class,'sendResetLinkEmail']);
Route::post('/password/reset',[ResetPasswordController::class,'reset']);

Route::get('/email/resend',[VerificationController::class,'resend'])->name('verification.resend');
Route::get('/email/verify/{id}/{hash}',[VerificationController::class,'verify'])->name('verification.verify');

Route::post('/resend-verify',[AuthController::class,'resendVerify']);

Route::group(['middleware'=>['actived.system','verified','auth:api']],function() {
    //Administrador
    Route::group(['middleware'=>['role']],function() {
        //Dependencias
            // Listar Dependencias
        Route::get('/dependences',[DependenceController::class,'listDependences']);
            // Listar Dependencias
        Route::get('/dependences/{id}',[DependenceController::class,'listDependency']);
            // Crear Dependencia
        Route::post('/dependences',[DependenceController::class,'store']);
            // Editar Dependencia
        Route::put('/dependences/{id}',[DependenceController::class,'update']);
            // Desactivar Dependencia
        Route::patch('/dependences/{id}',[DependenceController::class,'changeStatus']);

        //Usuarios
            //Listar Usuarios
        Route::get('/users/all', [AuthController::class,'users']);
            //Listar Usuario
        Route::get('/users/{id}', [AuthController::class,'user']);
            //Activar o Desactivar Usuarios
        Route::put('/users/active', [AuthController::class,'active']);

        //Escenarios
            //Crear Escenario
        Route::post('/escenarios/add',[EscenarioController::class,'create']);
            //Obtener Escenario
        Route::get('/escenarios/{id}',[EscenarioController::class,'show']);
            //Editar Escenario
        Route::put('/escenarios/{id}',[EscenarioController::class,'update']);
            //Borrar Escenario
        Route::delete('/escenarios/{id}',[EscenarioController::class,'destroy']);

        //Reservas
            //Obtener Reservas
        Route::get('/reservas',[ReservaController::class,'index']);
            //Obtener Reserva por id
            Route::get('/reservas/get/{id}',[ReservaController::class,'getReservationById']);
            //Obtener Estado de las reservas
        Route::get('/reservas/status/{status}',[ReservaController::class,'listStatusReservations']);
            //Obtener reservas del día
        Route::post('/reservas/today',[ReservaController::class,'listReservationsDay']);
            //Actualizar Reserva
        Route::put('/reservas/estado/{id}',[ReservaController::class,'updateStatus']);

        //Implementos
            //Crear Implemento
        Route::post('/implementos/add',[ImplementoController::class,'create']);
            //Obtener Implemento
        Route::get('/implementos/{id}',[ImplementoController::class,'show']);
            //Editar Implemeto
        Route::put('/implementos/{id}',[ImplementoController::class,'update']);
            //Borrar Implemento
        Route::delete('/implementos/{id}',[ImplementoController::class,'destroy']);

        //Prestamos
            //Obtener Prestamos
        Route::get('/prestamos',[PrestamoController::class,'index']);
            //Obtener Prestamo por id
        Route::get('/prestamos/get/{id}',[PrestamoController::class,'getLoanById']);
            //Obtener Estado de los prestamos
        Route::get('/prestamos/status/{status}',[PrestamoController::class,'listStatusLoans']);
            //Obtener prestamos del día
        Route::post('/prestamos/today',[PrestamoController::class,'listLoansDay']);
            //Actualizar prestamo
        Route::put('/prestamos/estado/{id}',[PrestamoController::class,'updateStatus']);
            //Actualizar estado del implemento del prestamo
        Route::put('/prestamos/estado/implemento/{id}',[PrestamoController::class,'updateStatusImplement']);
    });

    Route::group(['middleware'=>['role.implements']],function() {
        Route::get('/prestamos/user',[PrestamoController::class,'listLoansUser']);
        Route::get('/prestamos/user/{id}',[PrestamoController::class,'getLoanUser']);
        Route::post('/prestamos',[PrestamoController::class,'store']);
        Route::get('/implementos',[ImplementoController::class,'index']);
        Route::get('/prestamos/{id}',[PrestamoController::class,'show']);
    });
    //Obtener Reservas Usuario
    Route::get('/reservas/user',[ReservaController::class,'listReservationsUser']);
    Route::get('/reservas/{id}',[ReservaController::class,'show']);
    //Obtener Prestamos Usuario
    //Cerrar sesión
    Route::get('/logout',[AuthController::class,'logout']);
    Route::get('/escenarios',[EscenarioController::class,'index']);
    Route::post('/reservas',[ReservaController::class,'store']);
});
