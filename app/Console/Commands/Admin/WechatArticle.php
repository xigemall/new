<?php

namespace App\Console\Commands\Admin;

use App\Services\Admin\ArticleService;
use Illuminate\Console\Command;

class WechatArticle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wechat:article';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '微信获取文章';

    protected $article;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ArticleService $articleService)
    {
        parent::__construct();
        $this->article = $articleService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->article->makeWechatArticle();
        $this->info('全部微信获取文章成功');
    }
}
