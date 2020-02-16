<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/16
 * @time      : 下午5:42
 */

namespace Knight\Model;

use Knight\Component\Base;

class User extends Base
{
    public $table = 'users';

    public $fields = [
        'id' => [
            'column' => 'id',
            'pk' => true,
            'auto' => true,
            'type' => 'int'
        ],
        'username' => [
            'column' => 'username',
            'type' => 'varchar'
        ],
        'nickname' => [
            'column' => 'nickname',
            'type' => 'varchar'
        ],
        'password' => [
            'column' => 'password',
            'type' => 'varchar'
        ],
        'email' => [
            'column' => 'email',
            'type' => 'varchar',
            'default' => ''
        ],
        'created' => [
            'column' => 'created',
            'type' => 'int'
        ],
        'updated' => [
            'column' => 'updated',
            'type' => 'timestamp',
            'default' => 'current',
        ],
    ];

    public $indexes = [
        'username' => [
            'type' => 'unique',
            'column' => ['username']
        ],
        'email' => [
            'type' => 'key',
            'column' => ['email'],
        ]
    ];

}

return __NAMESPACE__;
