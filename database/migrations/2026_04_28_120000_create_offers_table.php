<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->double('final_price')->default(0);
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('offer_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('offer_id');
            $table->string('product_id');
            $table->integer('quantity')->default(1);
            $table->index('offer_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('offer_products');
        Schema::dropIfExists('offers');
    }
}
