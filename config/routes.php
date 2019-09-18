<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');




Router::post('/login','App\Controller\AuthController@login');   //后台登陆api ，限制ip，来源域名

Router::post('/upload','App\Controller\UploadController@upload'); //统一上传接口

//需AUTH 后台管理接口
Router::addGroup('/admin/',function (){


    Router::post('resetUserPassword','App\Controller\AuthController@resetUserPassword');//重置密码
    Router::post('logout','App\Controller\AuthController@logout');//退出登陆
    /**
     * 用户管理
     */
    Router::get('user/index','App\Controller\UserController@index');
    Router::get('user/info','App\Controller\UserController@info');
    Router::post('user/store','App\Controller\AuthController@register');//新增管理员
    Router::post('user/update/{id}','App\Controller\UserController@update');
    Router::get('user/delete/{id}','App\Controller\UserController@delete');
    Router::get('user/switch/{id}','App\Controller\UserController@disableSwitch');


    //权限管理
    Router::get('permissions','App\Controller\Auth\PermissionsController@index');
    Router::post('permissions/store','App\Controller\Auth\PermissionsController@store');
    Router::post('permissions/update/{id}','App\Controller\Auth\PermissionsController@update');
    Router::get('permissions/{id}','App\Controller\Auth\PermissionsController@desdory');
    Router::post('permissions/drap','App\Controller\Auth\PermissionsController@drap');
    Router::get('permission/options','App\Controller\Auth\PermissionsController@getOptions');

    //角色管理
    Router::get('role','App\Controller\Auth\RolesController@index');
    Router::post('role/store','App\Controller\Auth\RolesController@store');
    Router::post('role/update/{id}','App\Controller\Auth\RolesController@update');
    Router::get('role/{id}','App\Controller\Auth\RolesController@desdory');


}, ['middleware' => [\App\Middleware\Auth\FooMiddleware::class]]);