<?php
namespace Knight\Middleware;

use Hayrick\Http\Response;
use function PHPSTORM_META\type;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Riverline\MultiPartParser\Part;
use Hayrick\Http\Stream;

class Multipart implements MiddlewareInterface {


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request->bodyParser('multipart/form-data', function (Stream $body) use ($request) {
            $input = $body->getContents();
            $contentType = $request->getHeader('content-type');
            $data = [];
            if ($contentType && is_string($contentType)) {
                $input = 'Content-Type: ' . $contentType . "\r\n\r\n" . $input;
                $form = new Part($input);
                $parts = $form->getParts() ?? [];
                foreach ($parts as $key => $part) {
                    $name = $part->getName();
                    if ($name === 'files[]') {
                        continue;
                    }

                    $data[$name] = $part->getBody();
                }
            }

            return $data;
        });


        return $handler->handle($request);
    }
}