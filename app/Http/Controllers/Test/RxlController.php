<?php
/**
 * Author: 任小龙 Date:2020/2/9 Time:10:13
 */


namespace App\Http\Controllers\Test;


use App\Tools\Json\Json;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class RxlController extends Controller
{
    public function main()
    {
        $argc = microtime(true);
//        throw new \Exception('test123', 222);
        return Json::success(['a'=>$argc]);
        $this->testLog();
    }

    public function testLog()
    {
        Log::error('aaa',['a'=>'abc']);
        exit;
        $message = 'test';
        Log::emergency($message);
        Log::alert($message);
        Log::critical($message);
        Log::error($message);
        Log::warning($message);
        Log::notice($message);
        Log::info($message);
        Log::debug($message);
    }

}
