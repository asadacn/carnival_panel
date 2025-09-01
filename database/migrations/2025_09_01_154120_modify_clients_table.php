<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // নতুন কলামগুলো
            $table->string('secondary_contact', 15)->nullable()->after('contact');
            $table->string('onu_serial')->nullable()->after('onu_mac');
            $table->string('onu_brand')->nullable()->after('onu_serial');
            $table->boolean('onu_free')->default(false)->after('onu_brand');
            $table->boolean('onu_returned')->default(false)->after('onu_free');
            $table->enum('onu_owner', ['company', 'client'])->default('client')->after('onu_returned');

            $table->boolean('cable_returned')->default(false)->after('cable');
            $table->enum('cable_owner', ['company', 'client'])->default('company')->after('cable_returned');

            $table->enum('billing_type', ['prepaid', 'postpaid'])->default('prepaid')->after('cable_owner');
            $table->string('gps_location')->nullable()->after('billing_type');
            $table->text('comment')->nullable()->after('gps_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'secondary_contact',
                'onu_serial',
                'onu_brand',
                'onu_free',
                'onu_returned',
                'onu_owner',
                'cable_returned',
                'cable_owner',
                'billing_type',
                'gps_location',
                'comment'
            ]);
        });
    }
};
