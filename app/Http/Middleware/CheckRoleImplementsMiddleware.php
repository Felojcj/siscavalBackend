<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRoleImplementsMiddleware
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
        $role = auth()->user()->user_type;
        if($role === 'Admin' || $role ==='Profesor') {
            return $next($request);
        }else if($role === 'Alumno' || $role === 'Externo') {
            return response(['message'=>'User unauthorized']);
        }else {
            return response(['message'=>'User role does not exist in our system']);
        }
    }
}
