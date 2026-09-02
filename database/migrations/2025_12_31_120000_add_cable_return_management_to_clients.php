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
            $table->timestamp('closed_at')->nullable()->after('status');
            $table->timestamp('cable_returned_at')->nullable()->after('cable_returned');
            $table->text('cable_return_reason')->nullable()->after('cable_returned_at');
            $table->timestamp('onu_returned_at')->nullable()->after('onu_returned');
            $table->text('onu_return_reason')->nullable()->after('onu_returned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'closed_at',
                'cable_returned_at',
                'cable_return_reason',
                'onu_returned_at',
                'onu_return_reason',
            ]);
        });
    }
};
