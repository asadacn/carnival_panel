<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGpsAttributesToHotspotZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotspot_zones', function (Blueprint $table) {
            $table->string('gps_location')->nullable();
            $table->timestamp('gps_updated_at')->nullable();
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
            $table->dropColumn('gps_location');
            $table->dropColumn('gps_updated_at');
        });
    }
}
