<?php

namespace Knight\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Multipart implements MiddlewareInterface
{


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
//        $request->registerMediaTypeParser('multipart/form-data', function (Stream $body) {
//            $input = $body->getContents();
//            parse_str($input, $data);
//
//            return $data;
//        });
        $contentType = $request->getHeader('Content-Type');
        if (false !== strpos($contentType[0], 'json')) {
            $body = $request->getBody();
            $body = json_decode($body, true);
            $request = $request->withParsedBody($body);
        }


        return $handler->handle($request);
    }
}
