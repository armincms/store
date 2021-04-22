<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class ChangeStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_products', function (Blueprint $table) {
            $table->auth();   
            $table->unsignedBigInteger('brand_id')->nullable(); 

            $table->foreign('brand_id', 'foreign_brand_id_product')
                ->references('id')
                ->on('store_brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_products', function (Blueprint $table) {
            $table->dropForeign('foreign_brand_id_product'); 
            $table->dropAuth();   
            $table->dropColumn('brand_id'); 
        });
    }
}
