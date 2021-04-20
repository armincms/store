<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_attributes', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('group_id'); 
            $table->string('color')->nullable();
            $table->string('texture')->nullable();
            $table->softDeletes();

            $table->foreign('group_id')->references('id')->on('store_attribute_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_attributes');
    }
}
