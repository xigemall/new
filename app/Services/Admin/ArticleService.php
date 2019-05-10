<?php


namespace App\Services\Admin;


use App\Models\Wechat;
use Illuminate\Support\Facades\Artisan;

class ArticleService
{
    protected $showApi;

    public function __construct(IdataApiService $showApi)
    {
        $this->showApi = $showApi;
    }

    public function get()
    {

    }

    public function getWechatNum()
    {
        $data = Wechat::get();
        foreach ($data as $k=>$v){
             Artisan::call('article:create', [
                'id'=>$v->id,
                'wechat_num'=>$v->wechat_num
            ]);

        }
    }

}