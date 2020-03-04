<?php
/**
 * Author: 任小龙 Date:2020/3/4 Time:16:08
 */


namespace App\Data\Export;


use App\Tools\Redis\Redis;

class IDExport
{
    private static $startTime = 1582712339999; //strtotime('2020-02-26 10:18:59') . '999';

    public static function getID($type = 0)
    {
        $redis = Redis::getConn();
        while (true) {
            $id = self::createID($type);
            $res = $redis->setnx($id,1);
            if ($res) {
                $redis->expire($id,1);
                return $id;
            } else {
                p('失败');
                usleep(1000);//1毫秒后再试
            }
        }
        return false;
    }

    private static function createID($type)
    {
        switch (true) {
            case $type <= 0:
                $type = '00';
                break;
            case $type < 10:
                $type = '0' . $type;
                break;
            case $type > 99:
                $type = '99';
                break;
            default:
                $type = strval($type);
        }
        $now = (intval(microtime(true) * 1000));
        $id = ($now - self::$startTime) . ($type);
        return $id;
    }
}
