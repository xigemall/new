<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 友情链接
        Schema::create('blogrolls', function (Blueprint $table) {
            $table->increments('id');
            $table->char('title',50)->comment('标题')->default('');
            $table->string('link')->comment('链接地址')->default('');
            $table->unsignedTinyInteger('place')->comment('位置 0全局 1指定网站')->default(0);
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
        Schema::dropIfExists('blogrolls');
    }
}
