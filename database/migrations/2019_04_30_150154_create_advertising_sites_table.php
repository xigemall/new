<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisingSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 广告网站
        Schema::create('advertising_sites', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('advertising_id')->comment('广告ID')->index();
            $table->unsignedInteger('site_id')->comment('网站ID')->index();
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
        Schema::dropIfExists('advertising_sites');
    }
}
