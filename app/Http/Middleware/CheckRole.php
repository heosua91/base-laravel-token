<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $routeName = Route::currentRouteName();
        if (auth()->guard($guard)->user()->can($routeName, $routeName)) {
            return $next($request);
        }

        return response()->json(api_format([
            'message' => 'Forbidden'
        ], true), Response::HTTP_FORBIDDEN);
    }
}