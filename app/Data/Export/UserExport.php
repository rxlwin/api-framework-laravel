<?php
/**
 * Author: 任小龙 Date:2020/2/12 Time:19:16
 */


namespace App\Data\Export;


use App\Data\Redis\UserRedis;

class UserExport
{
    public static function getUserIdentity()
    {
        $identity = self::getUserId();
        if (empty($identity)) {
            $identity = ParamExport::getDeviceID();
        }
        if (empty($identity)) {
            $identity = ParamExport::getIP();
        }
        return $identity;
    }

    public static function getUserId()
    {
        $userId = 0;
        $token = ParamExport::getToken();
        if (!empty($token)) {
            $userInfo = (new UserRedis())->getInfoByToken($token);
            if (!empty($userInfo) && array_key_exists('user_id', $userInfo)) {
                $userId = $userInfo['user_id'];
            }
        }
        return $userId;
    }
}
