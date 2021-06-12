<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_orders', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('currency_code', 5)->default('IRR');
            $table->string('marked_as')->default('pending');
            $table->unsignedMediumInteger('tracking_code')->unique()->index();
            $table->string('token', 100)->unique()->index();
            $table->text('note')->nullable();
            $table->longPrice('total');
            $table->auth();
            $table->string('finish_callback')->nullable();
            $table->unsignedBigInteger('carrier_id')->nullable();
            $table->text('address')->nullable();
            $table->softDeletes();
            $table->timestamps();  

            $table->foreign('carrier_id')
                  ->references('id')->on('store_carriers'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_orders');
    }
}
