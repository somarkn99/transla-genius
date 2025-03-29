<?php

namespace CodingPartners\TranslaGenius\Middleware;

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
        $supportedLanguages = config('translaGenius.supported_languages', ['en']);
        $defaultLanguage = $supportedLanguages[0];

        $requestedLanguage = $request->hasHeader('Accept-Language')
            ? $request->header('Accept-Language')
            : $defaultLanguage;

        // Ensure the local is a string and supported
        $local = in_array($requestedLanguage, $supportedLanguages)
            ? $requestedLanguage
            : $defaultLanguage;

        app()->setLocale($local);

        return $next($request);
    }
}
