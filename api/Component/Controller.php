<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/20
 * @time      : 下午8:20
 */

namespace Knight\Component;


use Hayrick\Http\Request;
use Hayrick\Http\Response;

class Controller
{
    protected $response;

    public function __construct()
    {
        $this->response = new Response();
    }

}