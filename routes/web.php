<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return '嘉宾大学app-api';
});


//默认路由,当所有路由都不匹配时,走这里
Route::get('{any?}', function ($any) {
    throw new Exception('路由错误:' . $any, 9527);
})->where('any', '.*');
