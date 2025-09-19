<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty')->default(1);
            $table->integer('order_price')->default(0);
            $table->integer('order_subtotal')->default(0);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};
