<?php

namespace CodingPartners\AutoTranslator\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check header request and determine localization
        $local = $request->hasHeader('Accept-Language') ? $request->header('Accept-Language') : 'en';

        // Ensure the local is a string
        $local = is_array($local) ? 'en' : $local;

        app()->setLocale($local);

        return $next($request);
    }
}
