<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/index', 'HomeController@index')->name('admin.home');

    // 模板
    $router->resource('template', 'TemplateController');

    //模板详情列表
    $router->get('template-detail/{id}','TemplateDetailController@index');
    $router->get('template-detail/{id}/create','TemplateDetailController@create');
    $router->post('template-detail/{id}','TemplateDetailController@store');
    $router->get('template-detail/{id}/edit','TemplateDetailController@edit');
    $router->post('template-detail-delete','TemplateDetailController@delete');

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
    $router->post('wechat/{id}','WechatController@update');
    $router->resource('wechat','WechatController',[
        'except'=>['update']
    ]);

    // 友情
    $router->post('blogroll/{id}','BlogrollController@update');
    $router->resource('blogroll','BlogrollController',[
        'except'=>['update']
    ]);

    // 文章
    $router->post('article/{id}','ArticleController@update');
    $router->resource('article','ArticleController',[
        'except'=>['update']
    ]);
});
