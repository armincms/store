<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Orderable\Orderable;

class CreateStoreSaleablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_saleables', function (Blueprint $table) {  
            $table->longPrice('sale_price');
            $table->longPrice('old_price');
            $table->tinyInteger('count')->default(1);
            $table->string('name');
            $table->string('description')->nullable();
            $table->json('details')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('combination_id')->nullable();
            $table->bigIncrements('id'); 
            $table->timestamps();  

            $table
                ->foreign('order_id')
                ->references('id')->on('store_orders');

            $table
                ->foreign('product_id')
                ->references('id')->on('store_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_saleables');
    }
}
