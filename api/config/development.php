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
        'host' => '0.0.0.0',
        'port' => '5121',
        'setting' => [
            'daemonize' => false,
            'worker_num' => 1,
            'package_max_length' => 10 * pow(1024, 2)
        ]
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
            'pool' => false,
        ],
        'debug' => false,
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
    'upyun' => [
        'bucket' => 'knight-test',
        'username' => 'devil',
        'password' => 'knight123',
        'domain' => 'knight-test.test.upcdn.net',
    ],
    'mongo' => [
        'uri' => 'mongodb://localhost:27017/',
        'db' => 'knight',
    ]
];
