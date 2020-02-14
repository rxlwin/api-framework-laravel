<?php
/**
 * Author: 任小龙 Date:2020/2/9 Time:20:04
 */


namespace App\Tools\Json;


use App\Data\Enum\CodeMsg;
use App\Data\Export\ParamExport;
use App\Data\Export\UserExport;
use Illuminate\Support\Facades\Log;

class Json
{
    public static function success(array $data = [])
    {
        return self::export(CodeMsg::SUCCESS_CODE, '', $data, []);
    }

    public static function error(int $code, string $errMsg, array $errArr = [])
    {
        $msg = CodeMsg::getMsg($code);
        if (env('APP_DEBUG')) {
            $data = ['errMsg' => $errMsg];
        } else {
            $data = [];
        }
        return self::export($code, $msg, $data, $errArr);
    }

    private static function export($code, $msg, $data, $errDara)
    {
        if (empty($data)) {
            $data = (object)[];
        }
        $resId = ParamExport::getImpressionId();
        $res = [
            'res_id' => $resId,
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
        //写日志
        $logData = self::getParam();
        if (empty($errDara)) {
            $logData = array_merge($logData,['res'=>$res]);
            Log::stack(['info'])->info($resId, $logData);
        } else {
            $logData = array_merge($logData, ['res'=>$res], ['errorInfo' => $errDara]);
            Log::stack(['info','error'])->error($resId, $logData);
        }

        return response()->json($res);
    }

    private static function getParam()
    {
        $data = [
            'user_id' => UserExport::getUserId(),
            'device_id' => ParamExport::getDeviceID(),
            'IP' => ParamExport::getIP(),
            'route' => ParamExport::getLocalRoute(),
            'header' => ParamExport::getHeader(),
            'request' => ParamExport::getRequest(),
        ];
        return $data;
    }
}
