<?php
/**
 * Author: 任小龙 Date:2020/2/9 Time:11:40
 */


function p($var, $exit = false, $echo = false){
    static $i = 0;
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
    if (PHP_SAPI == 'cli') {
        $output = PHP_EOL . $output . PHP_EOL;
    } else {
        if ($i%2 == 0){
            $color = '#ccffcc';
        } else {
            $color = '#ff9999';
        }
        $i++;
        //#ff9999 粉色       #ccffcc 浅绿色
        $output = "<pre style='padding: 25px;background: " . $color . "'>" . $output . '</pre>';
    }
    if ($echo === false) {
        echo($output);
        if ($exit)
        {
            exit();
        }
    }

    return $output;
}

