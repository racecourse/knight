<?php
/**
 * @license   https://github.com/Init/licese.md
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/16
 * @time      : 下午5:42
 */
namespace Knight\Model;

use Knight\Component\Dao;

class User extends Dao
{
    public $table = 'users';

    public $fields = [
        'id' => ['column' => 'id', 'pk' => true, 'type' => 'int'],
        'username' => ['column' => 'username', 'type' => 'string'],
        'nickname' => ['column' => 'nickname', 'type' => 'string'],
        'password' => ['column' => 'password', 'type' => 'string'],
        'email' => ['column' => 'email', 'type' => 'string', 'default' => ''],
        'created' => ['column' => 'created', 'type' => 'int'],
        'updated' => ['column' => 'updated', 'type' => 'timestamp'],
    ];

}

return __NAMESPACE__;
