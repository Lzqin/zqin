<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//Route::rule('路由表达式','路由器地址','请求类型','路由参数（数组）','变量规则(数组)');
//GET,POST,DELETE,PUT
//Route::rule('hello','sample/Test/hello','GET',['https'=>false]);
//Route::rule('hello','sample/Test/hello','GET|POST',['https'=>false]);
//Route::post('hello/:id','sample/Test/hello');
//Route::post();
//Route::any();

Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');

Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');


Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');
Route::get('api/:version/product/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');

//Route::group('api/:version/product', function(){
//        Route::get('/by_category','api/:version.Product/getAllInCategory');
//        Route::get('/:id','api/:version.Product/getOne', [], ['id' => '\d+']);
//        Route::get('/recent','api/:version.Product/getRecent');
//});

Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

Route::post('api/:version/token/user', 'api/:version.Token/getToken');

Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');

Route::post('api/:version/order', 'api/:version.Order/placeOrder');

Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOreder');




