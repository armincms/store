<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Orderable\Orderable;

class CreateStoreCarrierRangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Down last implementation
        Schema::dropIfExists('store_carrier_city');

        Schema::create('store_carrier_range', function (Blueprint $table) {
            $table->bigIncrements('id');     
            $table->unsignedBigInteger('store_carrier_id')->nullable(); 
            $table->morphs('location'); 
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->price('cost');

            $table->foreign('store_carrier_id')->references('id')->on('store_carriers'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_carrier_range');
    }
}
