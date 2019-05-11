<?php


namespace App\Services\Admin;


use App\Models\Wechat;
use Illuminate\Support\Facades\Artisan;

class ArticleService
{
    /**
     *保存文章  （通过api获取微信文章）
     */
    public function makeWechatArticle()
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