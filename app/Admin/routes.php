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

    // 网站
    $router->resource('site', 'SiteController');
    // 上传网站logo
    $router->post('site-logo', 'SiteController@uploadSiteLogo');
    //上传网站ICO
    $router->post('site-ico', 'SiteController@uploadSiteIco');

    //广告
    $router->resource('advertising','AdvertisingController');
    $router->post('advertising-img','AdvertisingController@uploadAdvertisingImg');

    // 微信
    $router->resource('wechat','WechatController');

    // 友情
    $router->resource('blogroll','BlogrollController');

    // 文章
    $router->resource('article','ArticleController');
});
