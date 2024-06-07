<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        if(auth()->user()->type() == $userType){
            return $next($request);
        }

        // If the user doesn't have any of the required roles, return unauthorized response
        return response()->json(['error' => 'Forbidden'], 403);
    }
}
