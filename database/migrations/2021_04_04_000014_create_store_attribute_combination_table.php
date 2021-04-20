<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class CreateStoreAttributeCombinationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_attribute_combination', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('store_combination_id');
            $table->unsignedBigInteger('store_attribute_id');

            $table->foreign('store_combination_id')
                  ->references('id')->on('store_combinations'); 

            $table->foreign('store_attribute_id')
                  ->references('id')->on('store_attributes');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_attribute_combination');
    }
}
