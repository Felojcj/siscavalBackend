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
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\DetailController;
use App\Http\Controllers\Api\ValidValueController;
use App\Http\Controllers\Api\ScheduleController;


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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('exist','actived','verification');

Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);

Route::get('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::post('/resend-verify', [AuthController::class, 'resendVerify']);

Route::group(['middleware'=>['actived.system','verified','auth:api']],function() {
    //Administrador
    Route::group(['middleware'=>['role']],function() {
        //Dependencias
            // Listar Dependencias
        Route::get('/dependences', [DependenceController::class, 'listDependences']);
            // Listar Dependencias
        Route::get('/dependences/{id}', [DependenceController::class, 'listDependency']);
            // Crear Dependencia
        Route::post('/dependences', [DependenceController::class, 'store']);
            // Editar Dependencia
        Route::put('/dependences/{id}', [DependenceController::class, 'update']);
            // Desactivar Dependencia
        Route::patch('/dependences/{id}', [DependenceController::class, 'changeStatus']);

        //Usuarios
            //Listar Usuarios
        Route::get('/users/all', [AuthController::class, 'users']);
            //Listar Usuario
        Route::get('/users/{id}', [AuthController::class, 'user']);
            //Activar o Desactivar Usuarios
        Route::post('/test', [AuthController::class, 'active']);
            //Editar Usuario
        Route::put('/edit-user/{id}', [AuthController::class, 'update']);

        //Plantillas
            // Crear Plantilla
        Route::post('/templates', [TemplateController::class, 'store']);
            // Listar Plantillas
        Route::get('/templates', [TemplateController::class, 'listTemplates']);
            // Listar Plantillas
        Route::get('/templates/{id}', [TemplateController::class, 'listTemplate']);
            // Editar Plantilla
        Route::put('/templates/{id}', [TemplateController::class, 'update']);
            // Desactivar Plantilla
        Route::patch('/templates/{id}', [TemplateController::class, 'changeStatus']);

        //Detalles
            // Crear Detalle
        Route::post('/details', [DetailController::class, 'store']);
            // Listar Detalles
        Route::get('/details', [DetailController::class, 'listDetails']);
            // Listar Detalles por plantilla
        Route::get('/details/{id}', [DetailController::class, 'listDetail']);
            // Listar Detalle por id
        Route::get('/detail/{id}', [DetailController::class, 'listSelectedDetail']);
            // Editar Detalle
        Route::put('/details/{id}', [DetailController::class, 'update']);
            // Desactivar Detalle
        Route::patch('/details/{id}', [DetailController::class, 'changeStatus']);

        //Valores Validos
            // Crear Valor Valido
        Route::post('/valid-value', [ValidValueController::class, 'store']);
            // Listar Valores Validos
        Route::get('/valid-value', [ValidValueController::class, 'listValidValues']);
            // Listar Valor Valido
        Route::get('/valid-value/{id}', [ValidValueController::class, 'listValidValue']);
            // Listar Valor Valido por detalle
        Route::get('/valid-values/{id}', [ValidValueController::class, 'listValidDetailValue']);
            // Editar Valor Valido
        Route::put('/valid-value/{id}', [ValidValueController::class, 'update']);
            // Desactivar Valor Valido
        Route::patch('/valid-value/{id}', [ValidValueController::class, 'changeStatus']);

        //Programacion
            // Import Excel
        Route::post('/import', [ScheduleController::class, 'import']);
            // Creat Programacion
        Route::post('/schedule', [ScheduleController::class, 'store']);

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
