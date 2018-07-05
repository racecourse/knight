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

class ArticleTest extends AbstractTestCase
{

    public function testArticleList()
    {
        $this->visit('/posts', 'get')
        ->expectStatus(200)
        ->expectJson('code', 0);
    }
}