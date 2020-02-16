<?php
namespace Knight\Model;

use Knight\Component\Base;

class Comment extends Base
{
    public $table = 'comments';

    public $fields = [
        'id' => ['column' => 'id', 'pk' => true, 'type' => 'int'],
        'artId' => ['column' => 'art_id', 'type' => 'int'],
        'username' => ['column' => 'username', 'type' => 'int'],
        'email' => ['column' => 'email', 'type' => 'int'],
        'site' => ['column' => 'site', 'type' => 'varchar','default' => ''],
        'content' => ['column' => 'content', 'type' => 'varchar'],
        'like' => ['column' => 'like', 'type' => 'int', 'default' => 0],
        'unlike' => ['column' => 'unlike', 'type' => 'int', 'default' => 0],
        'created' => ['column' => 'created', 'type' => 'int'],
        'updated' => ['column' => 'updated', 'type' => 'timestamp', 'default' => 'current'],
    ];
}

return __NAMESPACE__;
