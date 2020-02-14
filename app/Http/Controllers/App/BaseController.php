<?php
/**
 * Author: 任小龙 Date:2020/2/12 Time:11:35
 */


namespace App\Http\Controllers\App;


use App\Data\Export\ParamExport;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->checkLogin();
    }

    public function checkLogin()
    {
        $localRoute = ParamExport::getLocalRoute();
        $token = ParamExport::getToken();
    }
}
