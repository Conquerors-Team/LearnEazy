<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $response = $next($request);
        $headers = [
            'Cache-Control' => 'nocache, no-store, max-age=0, must-revalidate',
            'Pragma','no-cache',
            'Expires','Fri, 01 Jan 1990 00:00:00 GMT',
        ];

        foreach($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;

        /*
        return $next($request)
            // Depending of your application you can't use '*'
            // Some security CORS concerns
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Max-Age', '10000')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
            */
    }
}


