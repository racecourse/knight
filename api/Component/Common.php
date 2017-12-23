<?php
/**
 * Created by PhpStorm.
 * User: tongh
 * Date: 2017/9/22
 * Time: 上午11:03
 */

namespace Knight\Component;

use Ben\Config;
use InvalidArgumentException;

trait Common
{
    public $component = [];
    
    public function __get($name)
    {
        if (isset($this->$name))
            return $this->$name;
        
        if (isset($this->component[$name]))
            return isset($this->component[$name]);
        
        $config = Config::get($name);
        $class = $config['class'];
        if (class_exists($class)){
            $this->component[$name] = new $class($config);
            return $this->component[$name];
        }else{
            throw new InvalidArgumentException('This class of' . $name . ' is not exist!');
        }
    }

    public function __isset($name)
    {
        // TODO: Implement __isset() method.
        return isset($this->$name);
    }

}