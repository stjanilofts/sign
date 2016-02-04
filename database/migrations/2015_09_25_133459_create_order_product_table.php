<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductTable extends Migration
{
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            
            $table->string('vnr');

            $table->integer('product_id');

            $table->integer('order_id');

            $table->integer('qty');

            $table->text('options');

            $table->integer('price');

            $table->integer('subtotal');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('order_product');
    }
}
