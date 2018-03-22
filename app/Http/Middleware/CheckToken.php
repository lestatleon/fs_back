<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authToken = $request->header('Authorization');

        $count = User::where('token', $authToken)->count();

        if ( 1 !== $count ) {
            return response()->json(['error' => 'no authenticated'])->setStatusCode(403);
        }

        return $next($request);
    }
}
