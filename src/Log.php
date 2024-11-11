<?php

namespace Affinidi;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    private static $logger;

    private static function getLogger()
    {
        if (self::$logger === null) {
            self::$logger = new Logger('affinidi');
            self::$logger->pushHandler(new StreamHandler(__DIR__ . '/affinidi.log', Logger::DEBUG));
        }
        return self::$logger;
    }

    public static function info($message, $data = [])
    {
        $logger = self::getLogger();
        $logger->info($message, $data);
    }

    public static function error($message, $data = [])
    {
        $logger = self::getLogger();
        $logger->error($message, $data);
    }
}
