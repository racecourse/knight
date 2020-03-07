<?php

namespace Knight\Model;

use Knight\Component\Base;

class Post extends Base
{
    protected $table = 'articles';

    public $fields = [
        'id' => [
            'column' => '_id',
            'pk' => true,
            'auto' => true,
            'type' => 'str'
        ],
        'userId' => [
            'column' => 'user_id',
            'type' => 'int'
        ],
        'cateId' => [
            'column' => 'cate_id',
            'type' => 'str'
        ],
        'title' => [
            'column' => 'title',
            'type' => 'varchar'
        ],
        'tags' => [
            'column' => 'tags',
            'type' => 'varchar'
        ],
        'views' => [
            'column' => 'views',
            'type' => 'int'
        ],
        'content' => [
            'column' => 'content',
            'type' => 'text'
        ],
        'permission' => [
            'column' => 'permission',
            'type' => 'int',
            'default' => 0
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

    public $indexes = [
        'userId' => [
            'type' => 'key',
            'column' => ['user_id']
        ],
        'cateId' => [
            'type' => 'key',
            'column' => ['cate_id'],
        ]
    ];
}

return __NAMESPACE__;
