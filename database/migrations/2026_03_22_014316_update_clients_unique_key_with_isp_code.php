<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClientsUniqueKeyWithIspCode extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['username']); // remove if error
            $table->unique(['isp_code', 'username'], 'unique_isp_client');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique('unique_isp_client');
            $table->unique('username');
        });
    }
}
