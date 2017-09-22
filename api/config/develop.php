<?php
/**
 * @license   MIT
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/16
 * @time      : 下午5:46
 */

return [
    'server' => [
        'host' => '127.0.0.1',
        'port' => '5001',
        'daemonize' => false,
        'worker_num' => 1,
    ],
    'db' => [
       'servers' => [
            'host' => '127.0.0.1',
            'port' => '3306',
            'user' => 'root',
            'password' => '123123',
            'dbname' => 'knight',
            'charset' => 'UTF8',
            'poolSize' => 50,
            'pool' => true,
        ],
        'debug' => true,
    ],
    'session' => [
        'class' =>'Marmot\Session',
        'issuer' => 'localhost',
        'audience' => '127.0.0.1',
        'expired' => 3600,
        'key' => 'develop',
    ],
    'jwt' => [
        'issuer' => 'mulberry10',
        'audience' => 'mulberry10.com',
        'expired' => 86400,
        'key' => 'test',
    ],
    'eclogue' => [
        'loader' => [
            'Photo' => 'Knight\Model\Photo',
        ]
    ],
    'env' => 'develop',
];