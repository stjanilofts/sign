<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle');
            $table->text('content');            
            $table->string('slug');
            $table->string('hlutur')->default('news');
            $table->string('link');
            $table->string('banner');
            $table->integer('fillimage')->default(0);
            $table->string('url');
            $table->string('type')->default('news');
            $table->dateTime('published_at');
            $table->integer('order')->default(0)->unsigned();
            $table->integer('parent_id')->default(0)->unsigned();
            //$table->foreign('parent_id')->references('id')->on('efni');
            $table->json('translations');
            $table->json('images');
            $table->json('extras');
            $table->json('files');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('news');
    }
}
