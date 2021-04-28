<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\MessageStatus;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateUser = Validator::make($request->all(),[
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:users',
            //'password' => 'required|alpha_num|confirmed|min:8',
            'position' => 'required|regex:/^[\pL\s\-]+$/u',
            'id_dependence' => 'required|integer',
            'is_admin' => 'required|boolean',
            'status' => 'required|boolean'
        ]);

        if($validateUser->fails()) {
            return response()
                ->json(['status'=>'500','data'=>$validateUser->errors()]);
        }

        $password = $this->generatePassword();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            //'password' => Hash::make($request->password),
            'password' => Hash::make($password),
            'position' => $request->position,
            'id_dependence' => $request->id_dependence,
            'is_admin' => $request->is_admin,
            'status' => $request->status
        ]);
        event(new Registered($user));
        Mail::to($user->email)->send(new MessageStatus($password));
        $accessToken = $user->createToken('authToken')->accessToken;

        return response()
                    ->json(['status' => '201', 'data' => $user,'access_token'=>$accessToken]);
    }

    public function login(Request $request) 
    {
        $user = auth()->user();
        $userRole = $user->is_admin;
        $accessToken = $user->createToken($user->email.'-'.now(),[$userRole]);
        return response()->json([
            'token' => $accessToken->accessToken,
            'name' => $user->name,
            'role' => $user->is_admin
        ]);
    }

    /*
    public function active(Request $request) 
    {
        $updateData = $request->validate([
            'email' => 'email|required',
            'is_active' => 'required|boolean'
        ]);

        $email = $request->email;
        $status = $request->is_active;
        $user = User::find(1)->where('email','=',$request->email)->first();

        if(!$user) {
            return response(['message'=>"No existe usuario con correo {$email}"]);
        }
        if($status === 1) {
            $message = "Usuario {$email} Activado";
        }else {
            $message = "Usuario {$email} Desactivado";
        }

        $user->is_active = $status;
        $user->save();
        return response(['message'=>$message]);
    }
    */

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'Successfully logged out']);
    }

    public function users() 
    {
        return User::all();
    }

    public function generatePassword() 
    {
        $cadena_base =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $cadena_base .= '0123456789';
        $password = '';
        $limite = strlen($cadena_base) - 1;

        for ($i=0; $i < 8; $i++)
            $password .= $cadena_base[rand(0, $limite)];

        return $password;
    }

    public function resendVerify(Request $request) 
    {
      $email = Validator::make($request->all(), [
          'email' => 'required|email'
      ]);

      $user = User::where('email',$email->getData())->first();

      if(!$user) {
          return response()
              ->json(['status' => '404', 'message' => 'Usuario no encontrado']);
      }

      if ($user->hasVerifiedEmail()) {
          return response(['status'=>'200','message'=>'Su correo ya se encuentra verificado']);
      }

      $user->sendEmailVerificationNotification();
      if ($request->wantsJson()) {
          return response(['status'=>'200','message' => 'Correo de Verificación Enviado']);
      }
  }
}
