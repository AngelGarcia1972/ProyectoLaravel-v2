<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $nonce = csrf_token();

        $policy = "default-src 'self'; "
                . "script-src 'self' 'nonce-{$nonce}'; "
                . "style-src 'self' 'unsafe-inline'; "
                . "img-src 'self' data: https:; "
                . "font-src 'self'; "
                . "connect-src 'self'; "
                . "frame-ancestors 'none'";

        $response->headers->set('Content-Security-Policy', $policy);

        return $response;
    }
}
