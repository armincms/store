<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_attribute_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('value'); 
            $table->unsignedBigInteger('attribute_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('attribute_id')->references('id')->on('store_attributes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_attribute_values');
    }
}
