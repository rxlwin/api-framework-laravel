<?php
/**
 * Author: 任小龙 Date:2020/2/12 Time:17:23
 */


namespace App\Data\Enum;


class CodeMsg
{
    const SUCCESS_CODE = 200;
    private static $list = [
        0 => '系统错误',
        200 => '',
        201 => '用户未登录'
    ];

    public static function getMsg($code)
    {
        $msg = '';
        if (isset(self::$list[$code])) {
            $msg = self::$list[$code];
        }
        return $msg;
    }
}
