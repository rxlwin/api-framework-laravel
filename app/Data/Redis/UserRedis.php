<?php
/**
 * Author: 任小龙 Date:2020/2/12 Time:19:18
 */


namespace App\Data\Redis;


class UserRedis
{
    public function getInfoByToken($token)
    {
        $key = 'token_' . $token;
        return cache($key);
    }
}
