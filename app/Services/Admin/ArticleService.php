<?php


namespace App\Services\Admin;


use App\Help\scws\PSCWS4;
use App\Models\Wechat;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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
        $arr = [',', '，', '。', '.', '/', '?', '？', ';', '；', ':', '：', "'", "‘", '"', '“', '”', '[', '【', ']', '】', '{', '}', '|', '`', '·', '~', '!', '！', '@', '#', '￥', '$', '%', '……', '^', '^', '&', '*', '（', '）', '(', ')', '-', '_', '——', '=', '+'];
        $title = str_replace($arr, '', $title);
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

    /**
     * 获取文章imageUrls文件资源
     * @param array $imageUrls
     * @return array
     */
    public function getImage(array $imageUrls)
    {
        $path = 'article';
        if (!Storage::disk('admin')->exists($path)) {
            Storage::disk('admin')->makeDirectory($path);
        }
        $name = date('YmdHis') . str_random(8);
        $newImageUrls = [];
        foreach ($imageUrls as $k => $v) {
            $imageUrl = $v;
            $result = $this->curl->get($imageUrl);
            $newName = $path . '/' . $name . $k . '.jpg';
            Storage::disk('admin')->put($newName, $result);
            $url = '/uploads/' . $newName;
            $newImageUrls[] = $url;
        }
        return $newImageUrls;
    }

}