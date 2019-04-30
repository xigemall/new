<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 网站模板
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name',50)->comment('模板名称')->default('')->unique();
            $table->string('description')->comment('模板描述')->default('')->nullable();
            $table->string('file')->comment('模板文件')->default('');
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
        Schema::dropIfExists('templates');
    }
}
