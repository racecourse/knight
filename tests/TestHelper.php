<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/7/5
 * @time: 上午10:34
 */

namespace Knight\Tests;

use Knight\Model\Post;
use Knight\Model\User;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class TestHelper
{

    public static $dataSet;

    public function addUser()
    {
        $dataSet = $this->getDataSet();
        $user = $dataSet->getTable('users');
        $admin = $user->getRow(0);
        $model = new User();
        $model->insert($admin);
    }


    public function addArticle()
    {
        $dataSet = $this->getDataSet();
        $article = $dataSet->getTable('articles');
        $count = $article->getRowCount();
        $model = new Post();
        for($i = 0; $i < $count; $i++) {
            $post = $article->getRow($i);
            $model->insert($post);
        }
    }


    public function getDataSet()
    {
        if (!self::$dataSet) {
            $path = dirname(__FILE__);
            $dataSet = new YamlDataSet($path . '/DataSet/data.yaml');
            self::$dataSet = $dataSet;
        }

        return self::$dataSet;
    }


}