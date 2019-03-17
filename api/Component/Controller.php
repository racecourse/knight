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


    protected function json(array $data, int $status = 200)
    {
        return new JsonResponse($data, $status);
    }

}

