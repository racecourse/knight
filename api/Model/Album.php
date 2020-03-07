<?php

namespace Knight\Model;

use Knight\Component\Base;

class Album extends Base
{

    public $table = 'albums';

    public $fields = [
        'id' => [
            'column' => '_id',
            'pk' => true,
            'auto' => true,
            'type' => 'int'
        ],
        'userId' => [
            'column' => 'user_id',
            'type' => 'int'
        ],
        'name' => [
            'column' => 'name',
            'type' => 'varchar',
            'length' => 155
        ],
        'detail' => [
            'column' => 'detail',
            'type' => 'varchar'
        ],
        'isShow' => [
            'column' => 'isShow',
            'type' => 'int',
            'default' => 1
        ],
        'created' => [
            'column' => 'created',
            'type' => 'int'
        ],
        'updated' => [
            'column' => 'updated',
            'type' => 'timestamp',
            'default' => 'current'
        ],
    ];
}

return __NAMESPACE__;
