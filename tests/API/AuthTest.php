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
use Knight\Tests\TestHelper;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Knight\Tests\Dao;

class AuthTest extends AbstractTestCase
{

    public $dataSet = null;

    public $user;

    public function setUp()
    {
        $this->user = TestHelper::addUser();
        parent::setUp();
    }

    public function tearDown()
    {
        $this->user->remove();
        parent::tearDown();
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
        $dataSet = TestHelper::getDataSet()->getTable('users');
        $row = $dataSet->getRow(0);
        $user = [
            'username' => $row['username'],
            'password' => 'some-password'
        ];

        $this->write($user)
            ->visit('/login', 'post', $user)
            ->expectStatus(401);


        $user['password'] = $row['password'];
        $this->write($user)
            ->visit('/login', 'post', $user)
            ->expectStatus(200)
            ->expectJson('message', 'ok');
    }
}