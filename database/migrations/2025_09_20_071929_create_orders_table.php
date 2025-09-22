<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('customer_name')->nullable();
            $table->dateTime('order_date');
            $table->integer('order_amount');    // jumlah sebelum diskon
            $table->integer('total_amount');    // jumlah akhir setelah diskon, pajak, dll
            $table->integer('order_change')->default(0);
            $table->enum('order_status', ['pending', 'paid', 'canceled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
