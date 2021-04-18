<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class CreateStoreCombinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_combinations', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('product_id'); 
            $table->price(); 
            $table->integer('weight')->nullable();
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();
            $table->integer('quantity')->nullable(); 
            $table->boolean('default')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('store_products');     
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_combinations');
    }
}
