<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('order_code')->nullable()->after('id');
            $table->timestamp('order_date')->nullable()->after('order_code');
            $table->integer('order_amount')->default(0)->after('customer_name');
            $table->integer('order_change')->default(0)->after('order_amount');
            $table->string('order_status')->default('paid')->after('order_change');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['order_code','order_date','order_amount','order_change','order_status']);
        });
    }
};
