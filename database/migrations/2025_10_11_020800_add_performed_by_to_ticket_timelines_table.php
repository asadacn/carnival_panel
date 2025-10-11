<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ticket_timelines', function (Blueprint $table) {
            $table->string('performed_by')->nullable()->after('action');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_timelines', function (Blueprint $table) {
            $table->dropColumn('performed_by');
        });
    }
};
