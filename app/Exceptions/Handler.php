<?php

namespace App\Exceptions;

use App\Tools\Json\Json;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
//        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $code = $exception->getCode();
        $msg = $exception->getMessage();
        $errorData = [
            'errCode' => $code,
            'errMsg' => $msg,
            'errFile' => $exception->getFile(),
            'errLine' => $exception->getLine(),
//            'trace' => $this->formatTrace($exception->getTrace()),
//            'trace' => $this->formatTraceString($exception->getTraceAsString())
//            'trace' => $exception->getTraceAsString()
        ];
        return Json::error($code, $msg, $errorData);

        //return parent::render($request, $exception);
    }

    private function formatTrace($traceList)
    {
        $res = [];
        for ($i = 0; $i < 10; $i++) {
            $trace = $traceList[$i];
            unset($trace['args']);
            unset($trace['type']);
            $res[] = $trace;
        }
        return $res;
    }

    private function formatTraceString($string)
    {
        $res = explode(PHP_EOL, $string);
        return $res;
    }
}
