<?php

namespace Knight\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;

class NotFound implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if (!$response instanceof ResponseInterface) {
            $response = new Response();
        }

        $response = $response->withStatus(404);
        $response->getBody()->write(json_encode(['message' => 'Not Found']));

        return $response;
    }
}
