<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpsHotspotZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotspot_zones', function (Blueprint $table) {
            $table->boolean('has_ups')->nullable();
            $table->string('usp_model')->nullable();
            $table->string('usp_adapter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotspot_zones', function (Blueprint $table) {
            $table->boolean('has_ups')->nullable();
            $table->string('usp_model')->nullable();
            $table->string('usp_adapter')->nullable();
        });
    }
}
