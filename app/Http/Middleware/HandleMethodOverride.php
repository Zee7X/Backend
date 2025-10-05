<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleMethodOverride
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('POST') && $request->has('_method')) {
            $method = strtoupper($request->input('_method'));
            $request->setMethod($method);
        }

        return $next($request);
    }
}
