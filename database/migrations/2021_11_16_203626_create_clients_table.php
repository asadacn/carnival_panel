<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('contact',15);
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('package',10)->nullable();
            $table->string('username');
            $table->string('password')->nullable();
            $table->macAddress('Onu_mac')->nullable();
            $table->integer('cable')->nullable();
            $table->date('expiration')->nullable();
            $table->string('status');
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
        Schema::drop('clients');
    }
}
