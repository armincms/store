<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class CreateStoreCarrierZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_carrier_zone', function (Blueprint $table) {
            $table->bigIncrements('id');     
            $table->unsignedBigInteger('store_carrier_id')->nullable(); 
            $table->unsignedBigInteger('location_zone_id')->nullable(); 
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->price();

            $table->foreign('store_carrier_id')->references('id')->on('store_carriers');
            $table->foreign('location_zone_id')->references('id')->on('location_zones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_carrier_zone');
    }
}
