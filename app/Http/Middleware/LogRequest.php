<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $data = [
            'Request Method' => $request->method(),
            'Request Path' => $request->path(),
            'Request Params' => $request->all(),
            'Request IP' => $request->ip(),
            'Origin' => $request->header('host'),
        ];

        Log::channel('requests')->info($data);

        return $next($request);
    }
}
