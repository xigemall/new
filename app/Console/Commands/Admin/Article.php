<?php

namespace App\Console\Commands\Admin;

use App\Services\Admin\IdataApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Article extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:create {id} {wechat_num}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取文章';

    protected $idata;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(IdataApiService $idataApiService)
    {
        parent::__construct();
        $this->idata = $idataApiService;
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
        $data = $this->idata->getIdataApiArticle($id,$wechatNum);
//        Log::info($data);
        if($data){
            $wechat = Wechat::findOrFail($id);
            foreach($data as $k=>$v){
                $article = new \App\Models\Article();
                $article->name = $wechat->name;
                $article->wechat_num = $wechatNum;
                $article->wechat_article_id = $v['id'];
                $article->title = $v['title'];
                $article->content = $v['content'];
                $article->html = $v['html'];
                $article->image_urls = $v['imageUrls'];
                $article->audio_urls = $v['audioUrls'];
                $article->video_urls = $v['videoUrls'];
                $article->comments = $v['comments'];
                $article->save();
            }
        }

    }
}
