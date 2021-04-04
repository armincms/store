<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class CreateStoreCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_carriers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('name');   
            $table->json('transit_time')->nullable();   
            $table->boolean('active')->default(false);   
            $table->boolean('free_shipping')->default(false);   
            $table->config();
            $table->enum('billing', array_keys(Helper::shippingBillings()))->default('price');  
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->timestamps();
            $table->softDeletes(); 

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
        Schema::dropIfExists('store_carriers');
    }
}
