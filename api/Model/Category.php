<?php
namespace Knight\Model;

use Knight\Component\Dao;

class Category extends Dao
{
    public $table = 'category';

    public $fields = [
        'id' => ['column' => 'id', 'pk' => true, 'type' => 'int'],
        'name' => ['column' => 'name', 'type' => 'string'],
        'created' => ['column' => 'created', 'type' => 'int'],
    ];
}

return __NAMESPACE__;
