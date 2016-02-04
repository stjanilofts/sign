<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subtitle');            
            $table->text('content');
            $table->string('slug');
            $table->integer('order')->default(0)->unsigned();
            $table->string('hlutur')->default('category');
            $table->integer('parent_id')->default(0)->unsigned();
            //$table->foreign('parent_id')->references('id')->on('flokkar');
            $table->json('translations');
            $table->integer('fillimage')->default(0);
            $table->json('extras');
            $table->json('options');
            $table->json('images');
            $table->json('shell');
            $table->json('skirt');
            $table->json('files');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('categories');
    }
}
