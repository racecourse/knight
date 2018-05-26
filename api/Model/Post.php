<?php
namespace Knight\Model;

use Knight\Component\Dao;

class Post extends Dao
{
    protected $table = 'articles';

    protected $fields = [
        'id' => ['column' => 'id', 'pk' => true, 'type' => 'int'],
        'userId' => ['column' => 'user_id', 'type' => 'int'],
        'cateId' => ['column' => 'cate_id', 'type' => 'int'],
        'title' => ['column' => 'title', 'type' => 'string'],
        'tags' => ['column' => 'tags', 'type' => 'string'],
        'views' => ['column' => 'views', 'type' => 'int'],
        'content' => ['column' => 'content', 'type' => 'string'],
        'permission' => ['column' => 'permission', 'type' => 'int', 'default' => 0],
        'created' => ['column' => 'created', 'type' => 'int'],
        'updated' => ['column' => 'updated', 'type' => 'timestamp'],
    ];
}

return __NAMESPACE__;
