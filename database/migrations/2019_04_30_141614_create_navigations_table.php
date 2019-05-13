<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavigationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 栏目（导航栏）
        Schema::create('navigations', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name',50)->comment('栏目名称')->defaullt('');
            $table->char('pinyin', 100)->comment('栏目名称拼音')->defaullt('');
            $table->unsignedBigInteger('site_id')->comment('网站ID')->index();
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
        Schema::dropIfExists('navigations');
    }
}
