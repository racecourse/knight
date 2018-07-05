<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/7/5
 * @time: 上午11:18
 */

namespace Knight\Tests\Controller;


use Knight\Model\User;
use Knight\Tests\AbstractTestCase;
use Knight\Tests\TestHelper;

class ArticleTest extends AbstractTestCase
{

    public $records = [];

    public $user;

    public function setUp()
    {
        $this->records = TestHelper::addArticle();
        $this->user = TestHelper::addUser();

    }

    public function tearDown()
    {
        foreach ($this->records as $record) {
            $record->remove();
        }

        $this->user->remove();
        parent::tearDown();
    }

    public function testArticleList()
    {
        $query = [
            'page' => 1,
            'pageSize' => 2,
            'order' => 'id',
        ];
        $this->visit('/posts', 'get', $query)
            ->expectStatus(200)
            ->expectJson('code', 0);


        $result = $this->getResponse();
        $this->assertArrayHasKey('data', $result);
        $data = $result['data'];
        $this->assertArrayHasKey('list', $data);
        $this->assertEquals($query['page'], $data['page']);
        $this->assertEquals($query['pageSize'], $data['pageSize']);
        $this->assertArrayHasKey('total', $data);
        foreach ($data['list'] as $key => $item) {
            $this->assertLessThanOrEqual(1, $item['permission']);
        }
    }

    public function testArticleDetail()
    {
        $id = 0;
        $this->visit('/posts/' . $id, 'get')
            ->expectStatus(400)
            ->expectJson('code', 1);
        $id = pow(1024, 5);
        $this->visit('/posts/' . $id, 'get')
            ->expectStatus(400)
            ->expectJson('code', 2);

        $id = $this->records[0]->id;
        $this->visit('/posts/' . $id, 'get')
            ->expectStatus(200)
            ->expectJson('code', 0);

        $result = $this->getResponse();
        $this->assertArrayHasKey('data', $result);
        $article = $result['data'];
        $this->assertEquals($id, $article['id']);
    }


    public function testAdminArticle()
    {
        $this->visit('/admin/article', 'get')
            ->expectStatus(401)
            ->expectJson('code', 10401);
        $dataSet = TestHelper::getDataSet()->getTable('users');
        $userInfo = $dataSet->getRow(0);
        $params = [
            'username' => $userInfo['username'],
            'password' => $userInfo['password'],
        ];
        (new User())->findOne(['username' => $params['username']]);
        $this->visit('/login', 'post', $params)
            ->expectStatus(200)
            ->expectJson('message', 'ok');
        $response = $this->getResponse();
        $this->assertArrayHasKey('data', $response);
        $data = $response['data'];
        $this->assertArrayHasKey('token', $data);
        $token = $data['token'];
        $this->header('Authorization', 'Bearer ' . $token)
            ->visit('/admin/article', 'get')
            ->expectStatus(200);

    }
}

