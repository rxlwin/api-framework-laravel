<?php
/**
 * Author: 任小龙 Date:2020/3/4 Time:21:57
 */


namespace App\Http\Controllers\App;


use App\Data\Export\IDExport;
use App\Http\Actions\ParamAction;
use App\Tools\Json\Json;

class IDController extends BaseController
{
    public function getID()
    {
        $rule = [
            ['type', 'int:[1,99]', 'int', null, null],
        ];
        $param = (new ParamAction())->getRequestFormatParam($rule);
        $res = [
            'id' => IDExport::getID($param['type'])
        ];
        return Json::success($res);
    }

}
