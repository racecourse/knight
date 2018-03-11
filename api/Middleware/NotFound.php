<?php
namespace Knight\Middleware;

use Hayrick\Http\Request;
use Hayrick\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NotFound implements MiddlewareInterface {


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        echo 'this is not found ' . PHP_EOL;
        $response = $handler->handle($request);
        if (!$response instanceof Response) {
            $response = new Response();
        }

        return $response->withStatus(404)
            ->json(['message' => 'Not Found']);
    }
}