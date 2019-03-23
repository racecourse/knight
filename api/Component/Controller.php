<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/20
 * @time      : 下午8:20
 */

namespace Knight\Component;



use Zend\Diactoros\Response\JsonResponse;

class Controller
{
    protected $response;

    protected $payload = [];

    protected $query = [];

    protected $params = [];


    protected function json(array $data, int $status = 200)
    {
        return new JsonResponse($data, $status);
    }


    protected function getPayload(string $name, $default = null)
    {
        if (isset($this->payload[$name])) {
            return $this->payload[$name];
        }

        return $default;
    }

    protected function getQuery(string $name, $default = null)
    {
        if (isset($this->query[$name])) {
            return $this->query[$name];
        }

        return $default;
    }


    protected function getParam(string $name, $default = null)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        }

        return $default;
    }
}

