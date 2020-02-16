<?php

namespace Knight\Model;

use Knight\Component\Base;

class Category extends Base
{
    public $table = 'category';

    public $fields = [
        'id' => [
            'column' => 'id',
            'pk' => true,
            'type' => 'int',
            'auto' => true,
        ],
        'name' => [
            'column' => 'name',
            'type' => 'varchar'
        ],
        'created' => [
            'column' => 'created',
            'type' => 'int'
        ],
    ];
}

return __NAMESPACE__;
