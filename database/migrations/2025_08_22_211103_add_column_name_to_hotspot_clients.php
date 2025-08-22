<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNameToHotspotClients extends Migration
{
     public function up(): void
    {
        Schema::table('hotspot_clients', function (Blueprint $table) {
            $table->integer('package_days')->nullable()->after('adrress');
            $table->timestamp('activated_at')->nullable()->after('package_days');
            $table->timestamp('expires_at')->nullable()->after('activated_at');
            $table->string('status')->default('inactive')->after('expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('hotspot_clients', function (Blueprint $table) {
            $table->dropColumn(['package_days','activated_at','expires_at','status']);
        });
    }

}
