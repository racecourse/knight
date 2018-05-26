<?php
namespace Knight\Model;

use Knight\Component\Dao;

class Album extends Dao
{

    public $table = 'albums';

    public $fields = [
        'id' => ['column' => 'id', 'pk' => true, 'type' => 'int'],
        'userId' => ['column' => 'user_id', 'type' => 'int'],
        'name' => ['column' => 'name', 'type' => 'string'],
        'detail' => ['column' => 'detail', 'type' => 'string'],
        'isShow' => ['column' => 'is_show', 'type' => 'int', 'default' => 1],
        'created' => ['column' => 'created', 'type' => 'int'],
        'updated' => ['column' => 'updated', 'type' => 'timestamp'],
    ];
}

return __NAMESPACE__;
