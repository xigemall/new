<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatCollectSiteNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 微信采集网站/栏目
        Schema::create('wechat_collect_site_navigations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('site_id')->comment('网站ID')->index();
            $table->unsignedInteger('navigation_id')->comment('栏目ID')->index();
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
        Schema::dropIfExists('wechat_collect_site_navigations');
    }
}
