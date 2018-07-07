<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/7/7
 * @time: 下午12:42
 */

namespace Knight\Tests\Api;

use Knight\Model\User;
use Knight\Model\Category;
use Knight\Tests\AbstractTestCase;
use Knight\Tests\TestHelper;

class CategoryTest extends AbstractTestCase
{

    public function testCreate()
    {

        $params = [];
        $this->header('Authorization', 'Bearer ' . $this->token)
            ->visit('/admin/category', 'post')
            ->expectStatus(400)
            ->expectJson('code', 1);
        $name = uniqid();
        $params['name'] = $name;
        $this->header('Authorization', 'Bearer ' . $this->token)
            ->visit('/admin/category', 'post', $params)
            ->expectStatus(200)
            ->expectJson('code', 0);

        $response = $this->getResponse();
        $this->assertArrayHasKey('data', $response);
        $data = $response['data'];

        $this->assertArrayHasKey('id', $data);
        $model = new Category();
        $category = $model->findById($data['id']);
        $this->assertInstanceOf(Category::class, $category);

        return $category;
    }

    /**
     * @depends testCreate
     */
    public function testDrop($category)
    {
        $id = 0;
        $this->header('Authorization', 'Bearer ' . $this->token)
            ->visit('/admin/category/' . $id, 'delete')
            ->expectStatus(400)
            ->expectJson('code', 1);
        $id = time();
        $this->header('Authorization', 'Bearer ' . $this->token)
            ->visit('/admin/category/' . $id, 'delete')
            ->expectStatus(404);

        $id = $category->id;
        $this->header('Authorization', 'Bearer ' . $this->token)
            ->visit('/admin/category/' . $id, 'delete')
            ->expectStatus(200)
            ->expectJson('code', 0);
    }

    public function testCategoryList()
    {
        $this->visit('/category', 'get')
            ->expectStatus(200)
            ->expectJson('code', 0);

        $response = $this->getResponse();
        $this->assertArrayHasKey('data', $response);
        $data = $response['data'];
        $this->assertLessThanOrEqual(20 , count($data));
    }
}

