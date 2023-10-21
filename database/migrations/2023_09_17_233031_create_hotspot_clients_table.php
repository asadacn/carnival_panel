<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotspotClientsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotspot_clients', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('contact')->nullable();
            $table->string('cable')->nullable();
            $table->string('cable_owner')->nullable();
            $table->string('onu_mac')->nullable();
            $table->string('onu_owner')->nullable();
            $table->string('adrress')->nullable();
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
        Schema::drop('hotspot_clients');
    }
}
