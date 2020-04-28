<?php

namespace Miraries\Apilyzer\Middleware;

use Closure;
use Illuminate\Http\Request;
//use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
use Miraries\Apilyzer\Apilyzer;
use Miraries\Apilyzer\ApilyzerFacade;

class InterceptAPICalls
{


    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param Route $route
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dump(Route::current()->getActionName());
//        dump(Route::current()->getActionMethod());
//        dump($this->route->getActionMethod());
//        dump(get_class($this->route->getController()));

//        if(!$request->wantsJson())
//        {
//            ApilyzerFacade::handleRequest();
//        }

        Apilyzer::handleRequest();

        return $next($request);
    }
}
