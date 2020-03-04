<?php
/**
 * Author: 任小龙 Date:2020/3/4 Time:16:59
 */


namespace App\Http\Actions;


use App\Data\Export\ParamExport;
use App\Tools\Params\Param;

class ParamAction
{






    public function getRequestFormatParam($rule)
    {
        $param = ParamExport::getRequest();
        return $this->getFormatParam($param, $rule);
    }
    /*
     * 校验参数
     */
    public function getFormatParam($params, $rule)
    {
        $object = new Param($params, $rule);
        list($error, $_, $params) = $object->getFormatParam();
        if (!empty($error)) {
            throw new \Exception($error[0],401);
        }
        return $params;
    }
}
