<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle');
            $table->text('content');
            $table->text('tech');
            $table->string('slug');
            $table->string('hlutur')->default('page');
            $table->string('url');
            $table->string('banner');
            $table->string('path');
            $table->string('blade_view');
            $table->integer('order')->default(0)->unsigned();
            $table->integer('topmenu')->default(1)->unsigned();
            $table->integer('parent_id')->default(0)->unsigned();
            $table->integer('accordion')->default(0)->unsigned();
            //$table->foreign('parent_id')->references('id')->on('efni');
            $table->json('translations');
            $table->json('images');
            $table->json('files');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('pages');
    }
}
