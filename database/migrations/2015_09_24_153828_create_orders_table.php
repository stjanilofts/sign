<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('confirmed')->default(0);

            $table->string('reference');
            $table->string('nafn');
            $table->string('netfang');
            $table->string('simi');
            $table->string('heimilisfang');
            $table->string('pnr');
            $table->string('stadur');
            $table->string('greidslumati');
            $table->string('afhendingarmati');

            $table->text('athugasemd');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('orders');
    }
}
