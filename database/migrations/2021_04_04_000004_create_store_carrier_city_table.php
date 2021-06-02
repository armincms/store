<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class CreateStoreCarrierCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Down last implementation
        Schema::dropIfExists('store_carrier_zone');

        Schema::create('store_carrier_city', function (Blueprint $table) {
            $table->bigIncrements('id');     
            $table->unsignedBigInteger('store_carrier_id')->nullable(); 
            $table->unsignedBigInteger('location_city_id')->nullable(); 
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->price('cost');

            $table->foreign('store_carrier_id')->references('id')->on('store_carriers');
            $table->foreign('location_city_id')->references('id')->on('location_cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_carrier_city');
    }
}
