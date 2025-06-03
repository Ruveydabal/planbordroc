<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        Log::info('CSRF Check', [
            'token' => $request->session()->token(),
            'header' => $request->header('X-CSRF-TOKEN'),
            'cookie' => $request->cookie('XSRF-TOKEN'),
            'url' => $request->url(),
            'method' => $request->method()
        ]);

        return parent::handle($request, $next);
    }
} 