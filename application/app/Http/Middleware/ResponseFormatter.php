<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $routeName = $request->route()?->getName();

        // Handle scribe postman/openapi response
        if (in_array($routeName, ['scribe.postman', 'scribe.openapi'])) {
            $appName = Str::slug(config('app.name', 'app'));
            if ($routeName === 'scribe.postman') {
                $response->headers->set('Content-Type', 'application/json');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $appName . '_postman-collection.json"');
            } else {
                $response->headers->set('Content-Type', 'application/x-yaml');
                $response->headers->set('Content-Disposition', 'attachment; filename="' . $appName . '_openapi-spec.yaml"');
            }
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
