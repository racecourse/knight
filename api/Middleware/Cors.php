<?php

namespace Knight\Middleware;

use Hayrick\Http\Request;
use Hayrick\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Cors implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $method = strtoupper($request->getMethod());
        if ($method !== "OPTIONS") {
            $response = $handler->handle($request);
        } else {
            $response = new Response();
        }

        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers',
                'Content-Type, Content-Length, Authorization, Accept, X-Requested-With')
            ->withHeader('Access-Control-Allow-Methods', 'PUT,POST,GET,DELETE,OPTIONS')
            ->withHeader('Content-Type', 'application/json;charset=utf-8');

        return $response;
    }
}