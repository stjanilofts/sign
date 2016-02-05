<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content');
            $table->string('slug');
            $table->string('hlutur')->default('product');
            $table->integer('order')->default(0)->unsigned();
            $table->integer('category_id')->default(0)->unsigned();
            //$table->foreign('category_id')->references('id')->on('categories');
            $table->string('vnr');
            $table->string('collection');
            $table->integer('price')->default(0);
            $table->integer('featured')->default(0);
            $table->integer('fillimage')->default(0);
            $table->json('images');
            $table->json('shell');
            $table->json('skirt');
            $table->json('options');
            $table->json('extras');
            $table->text('features');
            $table->text('tech');
            $table->text('sizes');
            $table->json('translations');
            $table->json('files');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('products');
    }
}
