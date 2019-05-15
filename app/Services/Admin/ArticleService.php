<?php


namespace App\Services\Admin;


use App\Help\scws\PSCWS4;
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
        foreach ($data as $k=> $v){
            Artisan::call('article:create', [
                'id'=>$v->id,
                'wechat_num'=>$v->wechat_num
            ]);
        }
    }

    /**
     * 获取分词
     * @return array
     */
    public function getArticleScws($title)
    {
        $pscws = new PSCWS4('utf8');
        $pscws->set_charset('utf-8');
        $pscws->set_dict(app_path('Help/scws/dict.utf8.xdb'));
        $pscws->set_rule(app_path('Help/scws/etc/rules.ini'));

        //使用：
        $pscws->send_text($title);
        $article = [];
        while ($some = $pscws->get_result()) {
            foreach ($some as $word) {
                $article[] = $word['word'];
            }
        }
        $pscws->close();
        return $article;
    }

    /**
     * 创建标签
     * @param array $tags
     * @param $article
     */
    public function makeTags(array $tags, $article)
    {
        $data = array_map(function ($v) {
            return ['name' => $v];
        }, $tags);
        $article->tags()->createMany($data);
    }


}