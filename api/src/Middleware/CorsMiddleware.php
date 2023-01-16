<?php

namespace App\Middleware;
class CorsMiddleware {
    public function __invoke($request, $response, $next)
    {
        if($request->is('options')){
            return $response->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Methods', 'DELETE, GET, OPTIONS, PATCH, POST, PUT')
                ->withHeader('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type')
                ->withStatus(204);
        }
        $response = $next($request, $response);
        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'DELETE, GET, OPTIONS, PATCH, POST, PUT')
            ->withHeader('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type');
        return $response;
    }
}