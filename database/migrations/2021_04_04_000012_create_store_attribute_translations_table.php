<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreAttributeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_attribute_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->resource('value'); 
            $table->visiting(); 
            $table->unsignedBigInteger('store_attribute_id'); 

            $table->foreign('store_attribute_id')
                ->references('id')->on('store_attributes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_attribute_translations');
    }
}
