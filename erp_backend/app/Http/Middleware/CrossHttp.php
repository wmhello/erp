<?php

namespace App\Http\Middleware;

use Closure;

class CrossHttp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', '*');

        $response->header('Access-Control-Allow-Headers', '*');

        $response->header('Access-Control-Allow-Methods', '*');

        $response->header('Access-Control-Allow-Credentials', 'true');

        return $response;

    }
}
