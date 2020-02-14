<?php
/**
 * Author: 任小龙 Date:2020/2/12 Time:11:41
 */


namespace App\Data\Enum;


class Login
{
    private static $notNeedLogin = [];

    public static function getNotNeedLoginList()
    {
        return self::$notNeedLogin;
    }
}
