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
use Knight\Model\User;
use Hayrick\Http\Request;
use Knight\Tests\AbstractTestCase;
use Knight\Tests\TestHelper;

class AuthTest extends AbstractTestCase
{

    public $user;


    public function requestProvider()
    {
        $builder = Relay::createFromGlobal();

        return Request::createRequest($builder);
    }


    /*
     * @after
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

        $this->visit('/login', 'post', $user)
            ->expectStatus(401);

        $user['password'] = $row['password'];
        $this->write($user)
            ->visit('/login', 'post', $user)
            ->expectStatus(200)
            ->expectJson('message', 'ok');
    }
}