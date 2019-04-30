<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogrollSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 友情链接指定网站
        Schema::create('blogroll_sites', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('blogroll_id')->comment('友情链接ID');
            $table->unsignedInteger('site_id')->comment('网站ID');
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
        Schema::dropIfExists('blogroll_sites');
    }
}
