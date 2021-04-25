<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; 

class CreateStoreAttributeGroupTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_attribute_group_translations', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->resource(); 
            $table->visiting(); 
            $table->unsignedBigInteger('store_attribute_group_id');

            $table->foreign('store_attribute_group_id', 'sagt_sag_id_foreign')
                ->references('id')->on('store_attribute_groups')
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
        Schema::dropIfExists('store_attribute_group_translations');
    }
}
