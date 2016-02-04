<?php

namespace App\Http\Middleware;

use Closure;

class ApiMiddleware
{
    public function handle($request, Closure $next){
        if($request->is('api*')) {
            \Config::set('session.driver', 'array');
            \Config::set('cookie.driver', 'array');
        }

        return $next($request);
    }
}