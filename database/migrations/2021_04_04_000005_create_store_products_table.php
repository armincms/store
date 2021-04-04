<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class CreateStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->bigIncrements('id');  
            $table->enum('product_type', array_keys(Helper::productTypes()))->default('physical');
            $table->boolean('active')->default(false);
            $table->boolean('available')->default(true);
            $table->boolean('only_online')->default(false);
            $table->boolean('display_price')->default(true);
            $table->boolean('on_sale')->default(false);
            $table->string('isbn')->nullable();
            $table->string('ean')->nullable();
            $table->string('upc')->nullable();
            $table->longPrice(); 
            $table->enum('product_status', array_keys(Helper::productStatuses()))->default('new');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->config();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tax_id')->references('id')->on('taxation_taxes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_products');
    }
}
