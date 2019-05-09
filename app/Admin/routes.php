<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    // 模板
    $router->resource('template', 'TemplateController');
    //模板详情列表
    $router->get('template-detail/{id}','TemplateDetailController@index');

    // 网站
    $router->post('site/{id}', 'SiteController@update');
    $router->resource('site', 'SiteController',[
        'except'=>[
            'update'
        ]
    ]);
    //获取网站栏目
    $router->get('site-navigation/{id}', 'SiteController@getSiteNavigation');

    //广告
    $router->post('advertising/{id}','AdvertisingController@update');
    $router->resource('advertising','AdvertisingController');

    // 微信
    $router->resource('wechat','WechatController');

    // 友情
    $router->post('blogroll/{id}','BlogrollController@update');
    $router->resource('blogroll','BlogrollController',[
        'except'=>['update']
    ]);

    // 文章
    $router->resource('article','ArticleController');
});
