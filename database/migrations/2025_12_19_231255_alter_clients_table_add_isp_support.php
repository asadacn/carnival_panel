<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ১. isp_code কলাম যোগ করা (যদি না থাকে)
        if (!Schema::hasColumn('clients', 'isp_code')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('isp_code')->default('carnival')->after('id')->index();
            });
        }

        // ২. পুরোনো ইনডেক্স সেফলি রিমুভ করা
        // এই অংশটিই আপনার এরর সলভ করবে
        $indexesToDrop = [
            'clients_username_unique',      // সিঙ্গেল ইউনিক
            'clients_isp_username_unique',  // কম্পোজিট ইউনিক
            'clients_isp_code_username_unique' // অন্য সম্ভাব্য নাম
        ];

        foreach ($indexesToDrop as $index) {
            try {
                // সরাসরি স্কিমা ব্যবহার না করে raw SQL দিয়ে ড্রপ করছি যাতে এরর হ্যান্ডেল করা যায়
                DB::statement("ALTER TABLE clients DROP INDEX {$index}");
            } catch (\Exception $e) {
                // ইনডেক্স না থাকলে আমরা এরর ইগনোর করব এবং কন্টিনিউ করব
                // 1091 হলো "Can't DROP; check that column/key exists" এরর কোড
            }
        }

        // ৩. ডুপ্লিকেট ডাটা ক্লিন করা (আগের লজিক)
        // Carnival vs Bijoy কনফ্লিক্ট মেটানো
        try {
            DB::statement("
                DELETE c_carnival
                FROM clients c_carnival
                INNER JOIN clients c_bijoy ON c_carnival.username = c_bijoy.username
                WHERE c_carnival.isp_code = 'carnival'
                AND c_bijoy.isp_code = 'bijoy'
                AND c_carnival.id != c_bijoy.id
            ");
        } catch (\Exception $e) {}

        // সাধারণ ডুপ্লিকেট রিমুভ (লেটেস্ট আইডি রেখে বাকিরা ডিলিট)
        try {
            DB::statement("
                DELETE t1 FROM clients t1
                INNER JOIN clients t2
                WHERE t1.id < t2.id AND t1.username = t2.username
            ");
        } catch (\Exception $e) {}

        // ৪. সবশেষে নতুন ইউনিক ইনডেক্স বসানো
        Schema::table('clients', function (Blueprint $table) {
            // ইনডেক্সটি আগে থেকেই আছে কিনা চেক করে নেওয়া ভালো
            try {
                // বা সরাসরি ট্রাই করা, যদি থাকে তবে এরর ক্যাচ করবে
                $table->unique('username', 'clients_username_unique');
            } catch (\Exception $e) {
                // যদি ইতিমধ্যে ইউনিক করা থাকে, তাহলে সমস্যা নেই
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // রিভার্স করার সময় সেফলি ড্রপ করা
        try {
            DB::statement("ALTER TABLE clients DROP INDEX clients_username_unique");
        } catch (\Exception $e) {}
    }
};
