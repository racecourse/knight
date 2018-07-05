<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/7/3
 * @time: 下午8:02
 */
namespace Knight\Tests\Controller;

use Courser\Relay;
use Hayrick\Http\Request;
use Knight\Tests\AbstractTestCase;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Knight\Tests\Dao;

class AuthTest extends AbstractTestCase
{

    public $dataSet = null;

    public $user;

    public function setUp()
    {
        $this->dataSet = $this->getDataSet();
        $user = $this->dataSet->getTable('users');
        $this->user = $user->getRow(0);
        parent::setUp();
    }

    public function tearDown()
    {
        $this->dataSet = null;
    }

    public function getDataSet()
    {
        if (!$this->dataSet) {
            $path = dirname(dirname(__FILE__));
            $dataSet =  new YamlDataSet($path . '/DataSet/data.yaml');
            $this->dataSet = $dataSet;
        }


        return $this->dataSet;
    }

    public function requestProvider()
    {
        $builder = Relay::createFromGlobal();

        return Request::createRequest($builder);
    }


    /*
     * @test
     */
    public function testLogin()
    {
        $this->visit('/login', 'post')
            ->expectStatus(400)
            ->expectJson('code', 1);

        $user = [
            'username' => 'somebody',
            'password' => 'everything'
        ];
        $this->visit('/login', 'post', $user)
            ->expectStatus(404)
            ->expectJson('code', 2);
        $user = [
            'username' => 'mulberry10',
            'password' => 'some-password'
        ];

        $this->write($user)
            ->visit('/login', 'post', $user)
            ->expectStatus(401);

        $user['password'] = 123123;
        $this->write($user)
            ->visit('/login', 'post', $user)
            ->expectStatus(200)
            ->expectJson('message', 'ok');
    }
}