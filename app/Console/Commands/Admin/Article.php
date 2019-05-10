<?php

namespace App\Console\Commands\Admin;

use Illuminate\Console\Command;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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


    }
}
