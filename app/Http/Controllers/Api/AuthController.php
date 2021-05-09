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
use App\Mail\MessageEdit;

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

    public function active(Request $request)
    {
        $email = $request->email;
        $status = $request->status;
        $user = User::find(1)->where('email','=', $request->email)->first();

        if(!$user) {
            return response(['message'=>"Usuario a desactivar no existe"], 404);
        }
  
        if($status === 0) {
            $status = 1;
            $message = "Usuario {$email} Activado";
        }else if($status === 1) {
            $status = 0;
            $message = "Usuario {$email} Desactivado";
        }

        $user->status = $status;
        $user->save();
        return response(['status'=>'200', 'message' => $message]);

      }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'Successfully logged out']);
    }

    public function users() 
    {
        $result = [];
        foreach (User::join('Dependences','users.id_dependence','=','dependences.id')
          ->select('users.id', 'users.name', 'users.email', 'users.position','users.is_admin','users.status', 'dependences.id as dependence_id', 'dependences.description', 'dependences.status as dependence_status')
          ->get() as $user) {
            $result[] = [
              'id' => $user->id,
              'username' => $user->name,
              'email' => $user->email,
              'position' => $user->position,
              'is_admin' => $user->is_admin,
              'status' => $user->status,
              'dependency' => [
                'id' => $user->dependence_id,
                'name' => $user->description,
                'status' => $user->dependence_status
              ]
            ];
          }

        return response()->json($result);
    }

    public function user($id)
    {
        $user = User::find($id);
        if($user){
            return response($user);
        }
        return response(['message'=>'No existe el usuario con el id '. $id], 404);
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

  public function update(Request $request, $id)
  {
      $user = User::where('id',$id)->first();

      if(!$user){
          return response()->json(['status'=>'404','data'=>'No existe el usuario con el id ' . $id]);
      }

      $validateUser = Validator::make($request->all(),[
          'name' => 'string',
          'email'=> 'email',
          'position'=> 'string',
          'id_dependence' => 'integer',
          'is_admin' => 'boolean',
          'status' => 'boolean'
      ]);

      if($validateUser->fails()) {
          return response()
              ->json(['status'=>'500','data'=>$validateUser->errors()]);
      }

      $request->name ? $user->name = $request->name: false;

      if ($request->email && $request->email <> $user->email) {
        Mail::to($request->email)->send(new MessageEdit($request->email));
      }

      $request->email ? $user->email = $request->email: false;
      $request->position ? $user->position = $request->position : false;
      $request->id_dependence ? $user->id_dependence = $request->id_dependence: false;
      $request->is_admin ? $user->is_admin = $request->is_admin: false;
      $request->status ? $user->status = $request->status: false;
      $user->save();

      return response()
                  ->json(['status' => '200', 'data'=>$user,'message' => "User Updated"]);
  }
}
