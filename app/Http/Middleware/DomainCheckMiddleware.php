<?php

namespace App\Http\Middleware;

use Closure;

class DomainCheckMiddleware
{
    public function handle($request, Closure $next)
    {
        $allowedDomains = ['carnival_panel.test', 'localhost', '127.0.0.1', '::1'];
        $currentDomain = $request->server('SERVER_NAME');

        if (!in_array($currentDomain, $allowedDomains)) {
            return response("Unauthorized server!", 403);
        }

        return $next($request);
    }
}
