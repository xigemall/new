<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 网站
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->char('title',200)->comment('网站标题')->default('')->index();
            $table->string('description',600)->comment('网站描述')->default('')->nullable();
            $table->string('keyword')->comment('网站关键字')->default('')->nullable();
            $table->char('domain',100)->comment('网站域名')->default('');
            $table->string('logo')->comment('网站LOGO图片')->default('')->nullable();
            $table->string('ico')->comment('网站ICO')->default('')->nullable();
            $table->unsignedTinyInteger('template_id')->comment('网站模板 0随机模板 (1)其它模板')->default(0);
            $table->unsignedInteger('visit')->comment('网站访问量')->default(0);
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
        Schema::dropIfExists('sites');
    }
}
