<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isTenant()) {
            return redirect()->route('tenant.login');
        }

        return $next($request);
    }
}
