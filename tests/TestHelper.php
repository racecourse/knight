<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/7/5
 * @time: 上午10:34
 */

namespace Knight\Tests;

use Ben\Config;
use Knight\Model\Post;
use Knight\Model\User;
use Knight\Model\Category;
use Knight\Middleware\Auth as JWTAuth;
use Mews\Model;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class TestHelper
{

    public static $dataSet;

    public static $admin;

    public static function addUser()
    {
        if (self::$admin) {
            return self::$admin;
        }

        $dataSet = self::getDataSet();
        $user = $dataSet->getTable('users');
        $admin = $user->getRow(0);
        $admin['password'] = password_hash($admin['password'], PASSWORD_DEFAULT);
        $model = new User();
        $schema = $model->getSchema();
        $sql = $schema->tableInfo();
        $model->query($sql);
        $record = $model->findOne(['username' => $admin['username']]);
        if ($record) {
            self::$admin = $record;
        } else {
            self::$admin = $model->insert($admin);
        }

        return self::$admin;
    }

    public function addCategory()
    {
        $dataSet = self::getDataSet();
        $cate = $dataSet->getTable('category');
        $record = $cate->getRow(0);
        $model = new Category();
        $schema = $model->getSchema();
        $sql = $schema->tableInfo();
        $model->query($sql);

        return $model->insert($record);
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

    public static function auth(Model $user)
    {
        $info = [
            'id' => $user->id,
            'username' => $user->username,
            'nickname' => $user->nickname,
            'email' => $user->email,
        ];
        $jwt = new JWTAuth(Config::get('jwt'));
        $token = $jwt->encode($info);

        return $token;
    }


}