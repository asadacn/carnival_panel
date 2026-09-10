<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateSmsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Add client_identifier column if not exists
        if (!Schema::hasColumn('sms_logs', 'client_identifier')) {
            Schema::table('sms_logs', function (Blueprint $table) {
                $table->string('client_identifier', 100)->nullable()->after('id');
            });

            // Migrate legacy username string in client_id to client_identifier
            DB::statement("UPDATE sms_logs SET client_identifier = client_id WHERE client_id IS NOT NULL");
        }

        // 2. Change client_id to BIGINT UNSIGNED NULL
        // First reset client_id to null so type alteration doesn't fail on string data
        DB::statement("UPDATE sms_logs SET client_id = NULL");
        DB::statement("ALTER TABLE sms_logs MODIFY COLUMN client_id BIGINT UNSIGNED NULL");

        // 3. Resolve existing client_id from clients table where clients.username = sms_logs.client_identifier
        DB::statement("UPDATE sms_logs sl JOIN clients c ON sl.client_identifier = c.username SET sl.client_id = c.id WHERE sl.client_identifier IS NOT NULL");

        // 4. Modify message column 'sms' to TEXT
        DB::statement("ALTER TABLE sms_logs MODIFY COLUMN sms TEXT NULL");

        // 5. Add new columns and foreign keys
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->foreignId('campaign_id')->nullable()->after('client_id')->constrained('sms_campaigns')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->after('campaign_id')->constrained('users')->nullOnDelete();

            $table->unsignedSmallInteger('character_count')->default(0)->after('sms');
            $table->unsignedTinyInteger('sms_count')->default(1)->after('character_count');
            $table->string('message_type', 30)->default('single')->after('sms_count');
            $table->string('encoding', 20)->default('unicode')->after('message_type');
            $table->string('gateway', 50)->default('mram')->after('encoding');
            $table->string('gateway_message_id', 191)->nullable()->after('gateway');
            $table->text('error_message')->nullable()->after('status');
            $table->json('gateway_response')->nullable()->after('error_message');
            $table->timestamp('sent_at')->nullable()->after('gateway_response');

            // Indexes for fast querying
            $table->index(['client_id', 'created_at']);
            $table->index(['contact', 'created_at']);
            $table->index(['campaign_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_logs', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['campaign_id']);
            $table->dropForeign(['user_id']);

            $table->dropIndex(['client_id', 'created_at']);
            $table->dropIndex(['contact', 'created_at']);
            $table->dropIndex(['campaign_id', 'status']);

            $table->dropColumn([
                'client_identifier',
                'campaign_id',
                'user_id',
                'character_count',
                'sms_count',
                'message_type',
                'encoding',
                'gateway',
                'gateway_message_id',
                'error_message',
                'gateway_response',
                'sent_at',
            ]);
        });

        DB::statement("ALTER TABLE sms_logs MODIFY COLUMN client_id VARCHAR(255) NULL");
        DB::statement("ALTER TABLE sms_logs MODIFY COLUMN sms VARCHAR(255) NULL");
    }
}
