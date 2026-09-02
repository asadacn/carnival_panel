<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('packages', 'isp_code')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->string('isp_code')->default('carnival')->after('descriptoin')->index();
            });
        }

        // Add composite unique to prevent duplicate package per ISP
        try {
            DB::statement('ALTER TABLE packages ADD CONSTRAINT packages_isp_title_unique UNIQUE (isp_code, title)');
        } catch (\Exception $e) {
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE packages DROP INDEX packages_isp_title_unique');
        } catch (\Exception $e) {
        }

        if (Schema::hasColumn('packages', 'isp_code')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->dropColumn('isp_code');
            });
        }
    }
};
