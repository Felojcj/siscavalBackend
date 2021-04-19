<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class VerificationMiddleware
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
        $user_verified = json_decode(User::select('email_verified_at')->where('email','=',$request->email)->first());

        if($user_verified->email_verified_at==null) {
            return response(['message'=>'El usuario no está activado'],401);
        }
        return $next($request);
    }
}
