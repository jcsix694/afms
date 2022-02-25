<?php

namespace App\Http\Middleware;

use App\Api\Core\Middleware\Jsonify;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $jsonifyMiddleware = new Jsonify();
        $jsonifyMiddleware->setHeaders($request);

        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
