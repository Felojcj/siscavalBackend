<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class UserExistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $remember = $request->remember;
        
        if(!auth()->attempt($loginData,$remember)) {
            return response(['message'=>'Usuario o Contraseña Incorrecta'],401);
        }
        
        return $next($request);
    }
}
