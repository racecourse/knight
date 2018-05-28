<?php
namespace Knight\Model;

class Error
{


    public $fields = [
        'code' => ['type' => 'int', 'default' => 500, 'comment' => 'server error code'],
        'message' => ['type' => 'string', 'default' => 'service error'],
    ];
}

return __NAMESPACE__;
