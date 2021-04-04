<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class CreateStorePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_package', function (Blueprint $table) {
            $table->bigIncrements('id');   
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('related_id');
            $table->integer('count')->default(1);
 
            $table->foreign('product_id')->references('id')->on('store_products');
            $table->foreign('related_id')->references('id')->on('store_products');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_package');
    }
}
