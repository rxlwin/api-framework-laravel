<?php
/**
 * Author: 任小龙 Date:2020/2/12 Time:11:39
 */


namespace App\Data\Export;


class ParamExport
{
    public static function getImpressionId()
    {
        static $id = null;
        if (is_null($id)) {
            $id = md5(microtime(true) . mt_rand(10000,99999));
        }
        return $id;
    }

    public static function getRequest()
    {
        return request()->all();
    }

    public static function getHeader()
    {
        return request()->header();
    }

    public static function getToken()
    {
        $token = request()->header('token', '');
        return $token;
    }

    public static function getUrl()
    {
        return request()->getRequestUri();
    }

    public static function getDeviceID()
    {
        $did = request()->header('did','');
        return $did;
    }

    public static function getIP()
    {
        $ip = request()->getClientIp();
        return $ip;
    }

    public static function getLocalRoute()
    {
        $route = request()->url();
        return $route;
    }
}
