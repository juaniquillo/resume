<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAcceptsJson
{
    /**
     * Ensure the client explicitly accepts a JSON response.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->wantsJson(),
            406,
            'This endpoint requires an "Accept: application/json" header.',
        );

        return $next($request);
    }
}
