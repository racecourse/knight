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
use Lily\Parser;

Config::load(APP_ROOT . '/api/config');
$app = require (APP_ROOT . '/api/routers.php');

$dirname = dirname(__DIR__);
$root = $dirname . '/docs';
$entrance = $dirname . '/docs/entrance.yaml';
$manure = new Manure($app, $root, $entrance);
$manure->dump();
$manure->dumpModel($dirname . '/api/Model');

$parser = new Parser($root, 'entrance.yaml');
$document = $parser->run();
$document = json_encode($document, JSON_UNESCAPED_UNICODE);
file_put_contents($root . '/knight.json', $document);



