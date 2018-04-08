<?php
namespace Knight\Model;

use Knight\Component\Dao;

class Photo extends Dao
{

    public $table = 'photos';

    public $fields = [
        'id' => ['column' => 'id', 'pk' => true, 'type' => 'int'],
        'userId' => ['column' => 'user_id', 'type' => 'int'],
        'albumId' => ['column' => 'album_id', 'type' => 'int', 'default' => 0],
        'url' => ['column' => 'url', 'type' => 'string'],
        'name' => ['column' => 'name', 'type' => 'string'],
        'attr' => ['column' => 'attr', 'type' => 'json'],
        'size' => ['column' => 'size', 'type' => 'int'],
        'panorama' => ['column' => 'panorama', 'type' => 'int', 'default' => 0],
        'isShow' => ['column' => 'is_show', 'type' => 'int', 'default' => 1],
        'created' => ['column' => 'created', 'type' => 'int'],
        'updated' => ['column' => 'updated', 'type' => 'timestamp'],
    ];
}