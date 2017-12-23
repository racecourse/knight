<?php
namespace Knight\Middleware;

use Hayrick\Http\Request;
use Hayrick\Http\Response;

class Cors {

    public function __invoke(Request $req, \Closure $next) {
        $method = strtoupper($req->getMethod());
        if ($method !== "OPTIONS") {
            $response = $next($req);
        } else {
            $response = new Response();
        }

        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Content-Length, Authorization, Accept, X-Requested-With')
            ->withHeader('Access-Control-Allow-Methods', 'PUT,POST,GET,DELETE,OPTIONS')
            ->withHeader('Content-Type', 'application/json;charset=utf-8');

        return $response;
    }
}