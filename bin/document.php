<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2018
 * @author: bugbear
 * @date: 2018/5/26
 * @time: 下午2:31
 */

define('APP_ROOT', dirname(dirname(__FILE__)));
require APP_ROOT . '/vendor/autoload.php';

use Ben\Config;
use Eclogue\Manjusaka\Manure;

Config::load(APP_ROOT . '/api/config');
$app = require (APP_ROOT . '/api/routers.php');

$dirname = dirname(__DIR__);
$entrance = $dirname . '/docs/entrance.yaml';
$manure = new Manure($app, $dirname . '/docs', $entrance);
$manure->dump();
$manure->dumpModel($dirname . '/api/Model');