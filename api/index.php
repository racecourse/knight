<?php
/**
 * @license   MIT
 * @copyright Copyright (c) 2017
 * @author    : bugbear
 * @date      : 2017/3/16
 * @time      : 下午1:16
 */


define('APP_ROOT', dirname(dirname(__FILE__)));
require APP_ROOT . '/vendor/autoload.php';

use Ben\Config;
use Knight\Controller\Auth;

Config::load(APP_ROOT . '/api/config');
$app = require (APP_ROOT . '/api/routers.php');

try {
    $env = Config::get('BEN_ENV');
    if ($env === 'production') {
        $_SERVER['REQUEST_URI'] = preg_replace('#/api/(.*?)$#', '/$1', $_SERVER['REQUEST_URI']);
    }
    
    
    $app->run();
} catch (Exception $err) {
    echo $err->getFile() . $err->getMessage() . $err->getLine();
}

