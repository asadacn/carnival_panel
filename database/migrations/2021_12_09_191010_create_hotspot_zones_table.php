<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotspotZonesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotspot_zones', function (Blueprint $table) {
            $table->id('id');
            $table->string('zone_id')->nullable();
            $table->string('zone_title');
            $table->string('device_brand')->nullable();
            $table->string('device_mac')->nullable();
            $table->string('device_serial')->nullable();
            $table->string('onu_mac')->nullable();
            $table->string('onu_brand')->nullable();
            $table->string('card_seller')->nullable();
            $table->string('status')->nullable();
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
        Schema::drop('hotspot_zones');
    }
}
