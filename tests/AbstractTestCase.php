<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/7/4
 * @time: 下午3:17
 */

namespace Knight\Tests;


use Ben\Config;
use DI\Container;
use Courser\Relay;
use Hayrick\Http\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use PHPUnit\DbUnit\DataSet\YamlDataSet;


abstract class AbstractTestCase extends TestCase
{

    public static $app;

    public $response;

    public $body = [];

    public $headers = [];


    public function __construct()
    {
        $this->headers = [
            'Content-Type' => 'application/json',
            'Content-Length' => 1024,
        ];
        $container = new Container();
        $resHandler = function () {
            $instance = new class extends \Courser\Terminator
            {
                public function end(ResponseInterface $response)
                {
                    return $response;
                }
            };

            return $instance;
        };
        $container->set('response.resolver', $resHandler);
        $reqHandler = function () {
            return $this->RelayProvider();
        };
        $container->set('request.resolver', $reqHandler);
        $app = self::createApp();
        $app->setContain($container);

        parent::__construct();
    }

    public static function createApp()
    {
        if (!self::$app) {
            $path = dirname(__DIR__);
            Config::load($path . '/api/config');
            self::$app = require $path . '/api/routers.php';
        }

        return self::$app;
    }


    public function visit(string $path, string $method, $params = [])
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $path;
        $_POST = array_merge($_POST, $params);
        $response = self::$app->run($path);
        $this->response = $response;

        return $this;
    }

    public function write(array $data)
    {
        $_POST = array_merge($_POST, $data);

        return $this;
    }

    public function expectStatus(int $statusCode)
    {
        $code = $this->response->getStatusCode();
        $this->assertEquals($code, $statusCode);

        return $this;
    }

    public function expectHeader($key, $value)
    {
        $header = $this->response->getHeader($key);
        $this->assertEquals($header, $value);
    }


    public function expectJson($key, $value)
    {
        $result = $this->response->getBody()->getContents();
        $result = json_decode($result, true);
        $this->assertArrayHasKey($key, $result);
        $this->assertEquals($value, $result[$key]);
    }

    public function getResponse()
    {
        $result = $this->response->getBody()->getContents();

        return json_decode($result, true);
    }



    public function buildRequest()
    {
        return function () {
            return $this->RelayProvider();
        };
    }

    public function RelayProvider()
    {
        return function () {
            $headers = $this->headers;
            $server = $_SERVER;
            $cookie = $_COOKIE;
            $files = $_FILES;
            $query = $_GET;
            $stream = fopen('php://temp', 'w+');
            $body = new Stream($stream);
            $body->write(json_encode($_POST));
            $relay = new Relay($server, $headers, $cookie, $files, $query, $body);

            return $relay;
        };
    }


}