<?php

namespace App\Api\Core\Middleware;

use Closure;
use Illuminate\Http\Request;

class Jsonify
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \ErrorException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->setHeaders($request);

        return $next($request);
    }

    public function setHeaders(Request $request)
    {
        if ($request->is('api/*')) $request->headers->set('Accept', 'application/json');
    }
}
