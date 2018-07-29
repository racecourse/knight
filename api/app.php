<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/5/26
 * @time: 下午2:26
 */

define('APP_ROOT', dirname(dirname(__FILE__)));
require APP_ROOT . '/vendor/autoload.php';

use Knight\Server;
use Ben\Config;

Config::load(APP_ROOT . '/api/config');
$app = require (APP_ROOT . '/api/routers.php');

$server = new Server($app);
$setting = Config::get('server');
$server->bind($setting['host'], $setting['port']);
$server->setting(Config::get('server.setting'));
$server->start();