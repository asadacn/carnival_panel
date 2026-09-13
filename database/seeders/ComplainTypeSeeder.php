<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplainTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'টিভি সেটিং সমস্যা / TV Setting Issue', 'description' => 'Customer facing TV or Android box setting issue'],
            ['name' => 'লাল বাতি জ্বলে / LOS Red Light', 'description' => 'LOS light blinking red (ONU signal loss)'],
            ['name' => 'অনু বন্ধ / ONU Offline (Power issue)', 'description' => 'ONU is turned off or has power issues'],
            ['name' => 'স্লো ইন্টারনেট / Slow Speed', 'description' => 'Internet speed is slow'],
            ['name' => 'লাইন কাটা / Cable Cut', 'description' => 'Physical cable damage or cut'],
            ['name' => 'রাউটার রিসেট / Router Configuration', 'description' => 'Router reset or needs re-configuration'],
            ['name' => 'কানেকশন ড্রপ / Connection Drop', 'description' => 'Internet connection dropping frequently'],
            ['name' => 'ওয়াইফাই পাসওয়ার্ড পরিবর্তন / Wifi Password Change', 'description' => 'Customer wants to change wifi password'],
            ['name' => 'বিলিং বা পেমেন্ট সমস্যা / Billing & Payment', 'description' => 'Invoice or payment issues'],
            ['name' => 'PPPoE লগইন সমস্যা / PPPoE Login Fail', 'description' => 'Unable to connect to PPPoE server'],
            ['name' => 'নতুন কানেকশন অনুরোধ / New Connection Request', 'description' => 'Request for new internet link'],
            ['name' => 'রুম ট্রান্সফার / রুম ট্রান্সফার', 'description' => 'Customer room transfer request'],
            ['name' => 'লাইন ট্রান্সফার / লাইনের পরিবর্তন', 'description' => 'Customer line transfer request'],
            ['name' => 'ক্যামেরা সমস্যা / ক্যামেরা সমস্যা', 'description' => 'Camera or CCTV related issue'],
            ['name' => 'প্যাচ কর্ড সমস্যা / প্যাচ কর্ড সমস্যা', 'description' => 'Patch cord not connected or damaged'],
            ['name' => 'LAN ক্যাবল সমস্যা / LAN ক্যাবল সমস্যা', 'description' => 'LAN cable disconnected or damaged'],
            ['name' => 'ONU অ্যাডাপ্টার সমস্যা / ONU অ্যাডাপ্টার সমস্যা', 'description' => 'ONU adapter power supply or adapter fault'],
            ['name' => 'রাউটার অ্যাডাপ্টার সমস্যা / রাউটার অ্যাডাপ্টার সমস্যা', 'description' => 'Router adapter or power adapter issue'],
            ['name' => 'ইন্টারনেট নেই / ইন্টারনেট নেই', 'description' => 'Completely no connectivity reported'],
        ];

        foreach ($types as $type) {
            DB::table('complain_types')->updateOrInsert(
                ['name' => $type['name']],
                [
                    'description' => $type['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
