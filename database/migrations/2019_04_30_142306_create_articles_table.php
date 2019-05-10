<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 文章管理
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('公众号名称')->default('')->nullable();
            $table->string('wechat_num')->comment('微信公众号')->default('')->nullable()->index();
            $table->string('wechat_article_id')->comment('文章ID')->index()->default('');
            $table->string('title')->comment('文章标题')->default('')->nullable();
            $table->longText('content')->comment('文章正文')->nullable();
            $table->longText('html')->comment('content对应的html代码')->nullable();
            $table->text('image_urls')->comment('内容图片链接列表')->nullable();
            $table->text('audio_urls')->comment('音频链接列表')->nullable();
            $table->text('video_urls')->comment('视频链接列表')->nullable();
            $table->text('comments')->comment(' 评论列表')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
