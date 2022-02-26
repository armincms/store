<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToStoreAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_addresses', function (Blueprint $table) { 
            $table->string('receiver'); 
            $table->unsignedBigInteger('zone_id');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_addresses', function (Blueprint $table) { 
            $table->dropColumn(['receiver', 'zone_id']);
        }); 
    }
}
