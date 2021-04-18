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
            $table->json('name'); 
            $table->string('field')->default(\Laravel\Nova\Fields\Text::class); 
            $table->timestamps();
            $table->softDeletes();
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
