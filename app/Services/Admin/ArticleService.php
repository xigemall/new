<?php


namespace App\Services\Admin;


use App\Models\Wechat;

class ArticleService
{
    protected $showApi;

    public function __construct(ShowApiService $showApi)
    {
        $this->showApi = $showApi;
    }

    public function get()
    {
        $this->showApi->getShowApiArticle('111');
    }

    public function getWechatNum()
    {
        $data = Wechat::get();
        dd($data->toArray());
    }

}