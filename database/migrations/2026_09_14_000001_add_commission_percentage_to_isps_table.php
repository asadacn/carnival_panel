<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionPercentageToIspsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isps', function (Blueprint $table) {
            $table->decimal('commission_percentage', 5, 2)->default(40.00)->after('signatory_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('isps', function (Blueprint $table) {
            $table->dropColumn('commission_percentage');
        });
    }
}
