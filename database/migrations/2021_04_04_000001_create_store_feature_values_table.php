<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreFeatureValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_feature_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('value'); 
            $table->unsignedBigInteger('feature_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('feature_id')->references('id')->on('store_features');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_feature_values');
    }
}
