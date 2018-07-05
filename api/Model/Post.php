<?php

namespace Knight\Model;

use Knight\Component\Dao;

class Post extends Dao
{
    protected $table = 'articles';

    public $fields = [
        'id' => [
            'column' => 'id',
            'pk' => true,
            'auto' => true,
            'type' => 'int'
        ],
        'userId' => [
            'column' => 'user_id',
            'type' => 'int'
        ],
        'cateId' => [
            'column' => 'cate_id',
            'type' => 'int'
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
