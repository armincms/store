<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Armincms\Store\Helper;

class ChangeStoreCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_carriers', function (Blueprint $table) {
            $table->price('cost');    
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->enum('ranges_apply', $approaches = array_keys(Helper::rangeApproaches()))
                    ->default(head($approaches));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_carriers', function (Blueprint $table) {  
            $table->dropColumn(['cost', 'min', 'max', 'ranges_apply']); 
        });
    }
}
