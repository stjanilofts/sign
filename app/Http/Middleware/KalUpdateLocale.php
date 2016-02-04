<?php

namespace App\Http\Middleware;

class KalUpdateLocale
{
    public function handle($request, \Closure $next)
    {
		if ($request->has('language')) {
			$request->session()->put('language', $request->get('language'));
		}

		\App::setLocale($request->session()->get('language') ?: config('app.locale'));
        
        return $next($request);
    }
}
