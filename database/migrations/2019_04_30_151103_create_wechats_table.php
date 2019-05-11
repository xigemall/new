<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //微信管理
        Schema::create('wechats', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name', 50)->comment('名称')->default('')->unique();
            $table->string('wechat_num')->comment('公众号')->default('')->index();
            $table->unsignedInteger('collect_num')->comment('采集数量')->default(0)->index();
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
        Schema::dropIfExists('wechats');
    }
}
