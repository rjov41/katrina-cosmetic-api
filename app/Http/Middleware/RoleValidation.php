<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,...$roles)
    {
        if($request->user()->authorizeRoles($roles)){
            return $next($request);
        }

        return response()->json(['status' => 'No tiene un rol permitido']);
    }
}
