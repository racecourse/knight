<?php

define('APP_ROOT', __DIR__);
require APP_ROOT . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;


$value = Yaml::parseFile(APP_ROOT . '/impress/impress.yaml');

foreach($value as $key => $value) {
  var_export($value);
  
}
