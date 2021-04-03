<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class CreateStoreProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_product_translations', function (Blueprint $table) {
            $table->bigIncrements('id');  
            $table->description();
            $table->unsignedBigInteger('store_product_id');
 
            $table->foreign('store_product_id')->references('id')->on('store_products');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_product_translations');
    }
}
