<?php

namespace Miraries\Apilyzer\Middleware;

use Closure;
use Illuminate\Http\Request;

class InterceptWebCalls
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        dump($request);

        return $next($request);
    }
}
