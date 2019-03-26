<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2019
 * @author: bugbear
 * @date: 2019/3/20
 * @time: 15:13
 */

namespace Knight\Middleware;

use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Prometheus\CollectorRegistry;
use Zend\Diactoros\Response\TextResponse;

class Prometheus implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $start = microtime( true);
        $uri = $request->getUri()->getPath();
        $registry = new CollectorRegistry(new InMemory());
        if ($uri === '/metrics') {
            $render = new RenderTextFormat();
            $metrics = $render->render($registry->getMetricFamilySamples());
            $response = new TextResponse($metrics, 200);
//            $response->withHeader('Content-type',  RenderTextFormat::MIME_TYPE);
            return $response;
        }

        $response = $handler->handle($request);
        $end = microtime(true);
        $duration = ($end - $start) * 1000;
        $statusCode = $response->getStatusCode();
        $context = $request->getAttribute('context');
        $routes = $context->getRoutes();
        $method = $request->getMethod();
        $labels = ['status_code', 'method', 'route'];
        foreach ($routes as $route) {
            $counter = $registry->registerCounter(
                'knight',
                'knight_request_total', 'Total number of HTTP requests',
                $labels
            );

            $counter->inc([$method, $statusCode, $route]);
            $histogram = $registry->registerHistogram(
                'knight',
                'knight_request_duration_seconds',
                'duration histogram of http responses',
                $labels,
                [0.005, 0.05, 0.1, 0.5, 1.5, 10]
            );
            $histogram->observe($duration, $labels);
        }

        return $response;
    }
}
