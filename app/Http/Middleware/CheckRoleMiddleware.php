<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $role = auth()->user()->is_admin;
        if($role === 1) {
            return $next($request);
        }else {
            return response(['message'=>'Usuario no autorizado']);
        }
    }
}
