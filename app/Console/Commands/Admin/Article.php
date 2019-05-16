<?php

namespace App\Console\Commands\Admin;

use App\Help\scws\PSCWS4;
use App\Models\Wechat;
use App\Services\Admin\ArticleService;
use App\Services\Admin\IdataApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\Models\Tag;

class Article extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:create {id} {wechat_num} {page_token=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取文章';

    protected $idata;

    protected $articleService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(IdataApiService $idataApiService, ArticleService $articleService)
    {
        parent::__construct();
        $this->idata = $idataApiService;
        $this->articleService = $articleService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        $wechatNum = $this->argument('wechat_num');
        $pageToken = $this->argument('page_token');
        $wechat = Wechat::findOrFail($id);
        $this->getArticleToSave($pageToken, $wechatNum, $wechat);

        $this->info($wechatNum . 'ok');

    }

    /**
     * 获取文章并保存
     * @param $pageToken
     * @param $wechatNum
     * @param $wechat
     */
    protected function getArticleToSave($pageToken, $wechatNum, $wechat)
    {
        $data = $this->idata->getIdataApiArticle($pageToken, $wechatNum);
//        Log::info($data);
//        $data = $this->getTestData($pageToken);
        if ($data && $data['data']) {
            foreach ($data['data'] as $k => $v) {
                $this->saveArticle($wechat, $v, $pageToken);
            }
            if ($pageToken == $data['pageToken'] && $data['hasNext']) {
                $this->getArticleToSave($pageToken, $wechatNum, $wechat);
            }
        }
    }

    /**
     * 保存文章
     * @param $wechat
     * @param $apiArticle
     * @param $pageToken
     */
    protected function saveArticle($wechat, $apiArticle, &$pageToken)
    {
        DB::transaction(function () use ($wechat, $apiArticle, &$pageToken) {
            $articleCount = \App\Models\Article::where('wechat_article_id', $apiArticle['id'])->count();
            if (!$articleCount) {
                // 数据库无该文章
                $article = new \App\Models\Article();
                $article->name = $wechat->name;
                $article->wechat_num = $wechat->wechat_num;
                $article->site_id = $wechat->site_id;
                $article->navigation_id = $wechat->navigation_id;
                $article->wechat_article_id = $apiArticle['id'];
                $article->title = $apiArticle['title'];
//                $article->view_count = $apiArticle['viewCount'] ? $apiArticle['viewCount'] : 0;
                $article->view_count = 0;
                $article->content = $apiArticle['content'];
                $article->html = $apiArticle['html'];
                $article->image_urls = $apiArticle['imageUrls'];
                $article->audio_urls = $apiArticle['audioUrls'];
                $article->video_urls = $apiArticle['videoUrls'];
                if ($apiArticle['commentCount']) {
                    $article->comments = $apiArticle['comments'];
                }
                $article->save();
                $wechat->increment('collect_num');
                $wechat->save();
                // tags保存
                $scws = $this->articleService->getArticleScws($article->title);
                $this->articleService->makeTags($scws, $article);

                $pageToken = $pageToken + 1;
            }
        });
    }


    /**
     * 测试数据
     * @return array
     */
    protected function getTestData($pageToken)
    {
        $data = [
            'pageToken' => $pageToken,
            'retcode' => 000000,
            'hasNext' => true,
            'data' => [
                [
                    'id' => 'B655BB2DFA525B364B7115C8DE19A2CBBB6D7A10',
                    'title' => '测试title',
                    'viewCount' => 66,
                    'content' => '测试的文章内容',
                    'html' => '<p>测试的文章内容</p>',
                    'imageUrls' => [
                        'http://image.baidu.com/search/detail?ct=503316480&z=undefined&tn=baiduimagedetail&ipn=d&word=%E5%9B%BE%E7%89%87&step_word=&ie=utf-8&in=&cl=2&lm=-1&st=undefined&hd=undefined&latest=undefined&copyright=undefined&cs=3300305952,1328708913&os=188573792,343995474&simid=4174703319,828922280&pn=0&rn=1&di=180180&ln=1737&fr=&fmq=1557540781511_R&fm=&ic=undefined&s=undefined&se=&sme=&tab=0&width=undefined&height=undefined&face=undefined&is=0,0&istype=0&ist=&jit=&bdtype=0&spn=0&pi=0&gsm=0&objurl=http%3A%2F%2Fpic37.nipic.com%2F20140113%2F8800276_184927469000_2.png&rpstart=0&rpnum=0&adpicid=0&force=undefined',
                        'http://image.baidu.com/search/detail?ct=503316480&z=undefined&tn=baiduimagedetail&ipn=d&word=%E5%9B%BE%E7%89%87&step_word=&ie=utf-8&in=&cl=2&lm=-1&st=undefined&hd=undefined&latest=undefined&copyright=undefined&cs=2153937626,1074119156&os=2973785003,2939226447&simid=4127171795,760771602&pn=3&rn=1&di=131560&ln=1737&fr=&fmq=1557540781511_R&fm=&ic=undefined&s=undefined&se=&sme=&tab=0&width=undefined&height=undefined&face=undefined&is=0,0&istype=0&ist=&jit=&bdtype=0&spn=0&pi=0&gsm=0&objurl=http%3A%2F%2Fk.zol-img.com.cn%2Fsjbbs%2F7692%2Fa7691515_s.jpg&rpstart=0&rpnum=0&adpicid=0&force=undefined'
                    ],
                    'audioUrls' => null,
                    'videoUrls' => null,
                    'comments' => null,
                ],
                [
                    'id' => 'B655BB2DFA525B364B7115C8DE19A2CBBB6D7A11',
                    'title' => '测试title1111',
                    'viewCount' => 36,
                    'content' => '测试的文章内容111',
                    'html' => '<p>测试的文章内容111</p>',
                    'imageUrls' => [
                        'http://image.baidu.com/search/detail?ct=503316480&z=undefined&tn=baiduimagedetail&ipn=d&word=%E5%9B%BE%E7%89%87&step_word=&ie=utf-8&in=&cl=2&lm=-1&st=undefined&hd=undefined&latest=undefined&copyright=undefined&cs=3300305952,1328708913&os=188573792,343995474&simid=4174703319,828922280&pn=0&rn=1&di=180180&ln=1737&fr=&fmq=1557540781511_R&fm=&ic=undefined&s=undefined&se=&sme=&tab=0&width=undefined&height=undefined&face=undefined&is=0,0&istype=0&ist=&jit=&bdtype=0&spn=0&pi=0&gsm=0&objurl=http%3A%2F%2Fpic37.nipic.com%2F20140113%2F8800276_184927469000_2.png&rpstart=0&rpnum=0&adpicid=0&force=undefined',
                        'http://image.baidu.com/search/detail?ct=503316480&z=undefined&tn=baiduimagedetail&ipn=d&word=%E5%9B%BE%E7%89%87&step_word=&ie=utf-8&in=&cl=2&lm=-1&st=undefined&hd=undefined&latest=undefined&copyright=undefined&cs=2153937626,1074119156&os=2973785003,2939226447&simid=4127171795,760771602&pn=3&rn=1&di=131560&ln=1737&fr=&fmq=1557540781511_R&fm=&ic=undefined&s=undefined&se=&sme=&tab=0&width=undefined&height=undefined&face=undefined&is=0,0&istype=0&ist=&jit=&bdtype=0&spn=0&pi=0&gsm=0&objurl=http%3A%2F%2Fk.zol-img.com.cn%2Fsjbbs%2F7692%2Fa7691515_s.jpg&rpstart=0&rpnum=0&adpicid=0&force=undefined'
                    ],
                    'audioUrls' => null,
                    'videoUrls' => null,
                    'comments' => null,
                ]
            ]
        ];
        return $data;
    }
}
