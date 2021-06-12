<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_addresses', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('name');
            $table->auth();
            $table->boolean('default')->default(false); 
            $table->unsignedBigInteger('city_id');
            $table->string('address', 500)->nullable();
            $table->string('zipcode')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->timestamps();

            $table->foreign('city_id')
                  ->references('id')->on('location_cities'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_addresses');
    }
}
