<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 广告
        Schema::create('advertisings', function (Blueprint $table) {
            $table->increments('id');
            $table->char('title',100)->comment('广告标题')->default('')->index();
            $table->string('img')->comment('广告图片')->default('');
            $table->string('link')->comment('链接')->default('');
            $table->unsignedInteger('place')->comment('位置 (0全局广告) (1-n 指定网站广告) ')->default(0);
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
        Schema::dropIfExists('advertisings');
    }
}
