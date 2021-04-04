<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class CreateStoreFeatureValueProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_feature_value_product', function (Blueprint $table) {
            $table->bigIncrements('id');   
            $table->unsignedBigInteger('store_product_id');
            $table->unsignedBigInteger('store_feature_value_id');
 
            $table->foreign('store_product_id')->references('id')->on('store_products');
            $table->foreign('store_feature_value_id')->references('id')->on('store_feature_values');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_feature_value_product');
    }
}
