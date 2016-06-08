<?php

namespace App\Http\Middleware;

use Closure;
use Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        /**
         * Handle an incoming request.
         *
         * Please add header('Access-Control-Allow-Origin: http://example.com');
         * & header('Access-Control-Allow-Credentials: true');
         * at the top of your route file.
         *
         * @param \Illuminate\Http\Request $request
         * @param \Closure                 $next
         *
         * @return mixed
         */
        if ($request->isMethod('options')) {
            return response('OK', 200)
                ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'accept, content-type, x-xsrf-token, x-csrf-token, Authorization'); // Add any required headers here
        }

        return $next($request);
    }
}