<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/7/5
 * @time: 上午11:18
 */

namespace Knight\Tests\Controller;


use Knight\Tests\AbstractTestCase;
use Knight\Tests\TestHelper;

class ArticleTest extends AbstractTestCase
{

    public $records = [];

    public $user;

    public function setUp()
    {
        $this->records = TestHelper::addArticle();
        parent::setUp();

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
        $this->header('Authorization', 'Bearer ' . $this->token)
            ->visit('/admin/article', 'get')
            ->expectStatus(200);
    }

    public function CreateEditArticle()
    {
        $params = [];
        $this->visit('/admin/article', 'post')
            ->expectStatus(400)
            ->expectJson('code', 1);
        $title = uniqid();
        $params['title'] = $title;
        $this->visit('/admin/article', 'post')
            ->expectStatus(400)
            ->expectJson('code', 4);
    }
}

