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
    use Common;
    
    public $request;

    public $response;

    protected $payload  = [];

    public function __construct(Request $req, Response $res)
    {
        $this->request = $req;
        $this->response = $res;
        $this->payload = $this->request->getParsedBody();
    }

    public function body($key = null, $default = null) {
        return $this->payload[$key] ?? $default;
    }
}