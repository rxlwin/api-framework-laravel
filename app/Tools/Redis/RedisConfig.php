<?php
/**
 * Author: 任小龙 Date:2019-09-03 Time:22:12
 */


namespace App\Tools\Redis;


class RedisConfig
{
    public static function getConfig()
    {
        $config = [
            'host' => env('REDIS_HOST'),
            'port' => env('REDIS_PORT'),
            'auth' => env('REDIS_PASSWORD'),
        ];
        return $config;
    }
}
