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

    public static function addUser()
    {
        $dataSet = self::getDataSet();
        $user = $dataSet->getTable('users');
        $admin = $user->getRow(0);
        $admin['password'] = password_hash($admin['password'], PASSWORD_DEFAULT);
        $model = new User();
        $schema = $model->getSchema();
        $sql = $schema->tableInfo();
        $model->query($sql);
        return $model->insert($admin);
    }


    public static function addArticle()
    {
        $dataSet = self::getDataSet();
        $article = $dataSet->getTable('articles');
        $count = $article->getRowCount();
        $model = new Post();
        $schema = $model->getSchema();
        $sql = $schema->tableInfo();
        $model->query($sql);
        $records = [];
        for($i = 0; $i < $count; $i++) {
            $post = $article->getRow($i);
            $records[] = $model->insert($post);
        }

        return $records;
    }


    public static function getDataSet()
    {
        if (!self::$dataSet) {
            $path = dirname(__FILE__);
            $dataSet = new YamlDataSet($path . '/DataSet/data.yaml');
            self::$dataSet = $dataSet;
        }

        return self::$dataSet;
    }



    public static function removeUser() {
        $dataSet = self::getDataSet();
        $user = $dataSet->getTable('users');
        $admin = $user->getRow(0);
        $model = new User();
        $record = $model->findOne(['username' => $admin['username']]);
        if ($record) {
            $record->remove();
        }
    }


}