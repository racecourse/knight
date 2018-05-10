<?php
namespace Knight\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hayrick\Http\Stream;

class Multipart implements MiddlewareInterface {


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request->bodyParser('multipart/form-data', function (Stream $body)  {
            $input = $body->getContents();
            parse_str($input, $data);

            return $data;
        });


        return $handler->handle($request);
    }
}