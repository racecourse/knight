<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/7/3
 * @time: 下午6:54
 */

namespace Knight\Tests;

use Mews\Model;

class Dao extends Model
{

    public $table = '';

    public $fields = [];

    public function __construct(array $config)
    {

    }
}