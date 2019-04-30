<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteNavigationArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 网站 、栏目 关联的文章
        Schema::create('site_navigation_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('site_id')->comment('网站ID')->index();
            $table->unsignedInteger('navigation_id')->comment('栏目ID')->index();
            $table->unsignedInteger('article_id')->comment('文章ID')->index();
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_navigation_articles');
    }
}
