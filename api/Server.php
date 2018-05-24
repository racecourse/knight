<?php

/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/2/24
 * @time: 下午1:25
 */

namespace Knight;

use Closure;
use Courser\App;
use Courser\Relay;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Server as SwooleServer;
use Swoole\Http\Request;
use Hayrick\Http\Stream;
use DI\Container;


class Server
{

    protected $worker = 1;
    protected $task = 1;
    protected $daemonzie = true;
    protected $server;
    protected $host = '127.0.0.1';
    protected $port = '8179';
    protected $setting = [];
    protected $events = [];
    protected $scheduler;
    protected $container;
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->container = new Container();
        $this->container->set('response.resolver', function () {
            return $this->buildResponse();
        });
        $this->container->set('request.resolver', function () {
            return $this->buildRequest();
        });

        $this->app->setContainer($this->container);
    }
    /**
     * @param $host
     * @param $port
     */
    public function bind($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }
    /**
     * @param array $setting
     */
    public function setting($setting = [])
    {
        $setting = is_array($setting) ? $setting : [];
        $this->setting = $setting;
    }
    /**
     * @param string $field
     * @param $value
     */
    public function register(string $field, $value)
    {
        $this->events[$field] = $value;
    }


    public function buildResponse()
    {

        $instance = new class extends \Courser\Terminator {

            public function end(ResponseInterface $response) {
                $headers = $response->getHeaders();
                foreach ($headers as $key => $header) {
                    $this->origin->header($key, $header);
                }
                $this->origin->status($response->getStatusCode());
                $data = $response->getContent();
                $this->origin->end($data);
            }
        };

        return $instance;
    }

    public function  getRelay(Request $request)
    {
        $server = $request->server ?? [];
        $cookie = $request->cookie ?? [];
        $files = $request->files ?? [];
        $query = $request->get ?? [];
        $headers = $request->header ?? [];
        $stream = fopen('php://temp', 'w+');
        $source = $request->post ?: $request->rawContent();
        if (!empty($source)) {
            if (is_array($source)) {
                $source = http_build_query($source);
            }

            fwrite($stream, $source);
        }

        if (!isset($server['http_host']) && isset($headers['http_host'])) {
            $server['http_host'] = $headers['https_host'];
        }

        $body = new Stream($stream);
        $relay = new Relay($server, $headers, $cookie, $files, $query, $body);

        return $relay;
    }

    public function buildRequest(): Closure
    {
        return function(Request $request) {
           return $this->getRelay($request);
        };
    }


    /**
     * @param $req
     * @param $res
     * @throws \Throwable
     */
    public function mount($req, $res)
    {
        $path = $req->server['request_uri'] ?? '/';
        $this->app->run($path, $req, $res);
    }


    public function start()
    {
        $this->server = new SwooleServer($this->host, $this->port);
        $tmpDir = sys_get_temp_dir();
        $config = [
            'daemonize' => false,
            'http_parse_post' => false,
            'dispatch_mode' => 3,
            'upload_tmp_dir' => $tmpDir,
            'worker_num' => 1,
        ];
        $config = array_merge($config, $this->setting);
        $this->app->config($config);
        $this->server->set($config);
        $this->server->on('Request', [$this, 'mount']);
        foreach ($this->events as $key => $value) {
            $this->server->on($key, $value);
        }

        $this->server->start();
    }
}