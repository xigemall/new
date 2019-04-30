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
            $table->string('account')->comment('微信号')->default('')->nullable();
            $table->char('type',100)->comment('新榜公众号类别')->default('')->nullable();
            $table->char('author',100)->comment('作者')->default('')->nullable();
            $table->string('image_url')->comment('图文中头图链接')->default('')->nullable();
            $table->string('original_url')->comment('原文链接')->default('')->nullable();
            $table->string('audio_url')->comment('图文中含音频链接')->default('')->nullable();
            $table->string('update_time')->comment('更新时间')->default('')->nullable();
            $table->string('title')->comment('图文标题')->default('')->nullable();
            $table->text('summary')->comment('图文摘要')->nullable();
            $table->string('public_time')->comment('发布时间')->default('')->nullable();
            $table->string('info_url')->comment('图文链接')->default('')->nullable();
            $table->unsignedTinyInteger('is_original')->comment('是否声明原创，1：原创，0：非原创')->default(0)->nullable();
            $table->unsignedInteger('read_num')->comment('阅读数')->default(0)->nullable();
            $table->unsignedInteger('like_num')->comment('点赞数')->default(0)->nullable();
            $table->text('content')->comment('正文（需要正文时返回）')->nullable();
            $table->text('keywords')->comment('基于正文提取的关键词（需要正文时返回）')->nullable();
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
