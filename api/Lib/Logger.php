<?php
/**
 * @license MIT
 * @copyright Copyright (c) 2019
 * @author: bugbear
 * @date: 2019/4/15
 * @time: 10:30
 */
namespace Knight\Lib;

use Monolog\Logger as MonoLogger;
use Monolog\Handler\StreamHandler;


class Logger
{

    protected $logger;

    protected static $instance = null;

    public function __construct()
    {
        // create a log channel
        $path = \Ben\Config::get('logger.path');
        $filename = $path . date('Y-m-d') . '.log';
        $log = new MonoLogger('name');
        $log->pushHandler(new StreamHandler($filename, MonoLogger::DEBUG));
        $this->logger = $log;
    }

    public function getLogger(): MonoLogger
    {
        return $this->logger;
    }

    /**
     * @return Logger
     */
    public static function getInstance(): self
    {
        if (self::$instance) {
            return self::$instance;
        }

        return self::$instance = new self();
    }

    public static function info(...$params)
    {
        $logger = self::getInstance()->getLogger();
        $logger->info(...$params);
    }

    public static function error(...$params)
    {
        $logger = self::getInstance()->getLogger();
        $logger->error(...$params);
    }

    public static function __callStatic($name, $arguments)
    {
        $logger = self::getInstance()->getLogger();
        $logger->$name(...$arguments);
    }
}
