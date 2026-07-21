<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplainTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('complain_types')->insert([
            ['name' => 'টিভি সেটিং সমস্যা / TV Setting Issue', 'description' => 'Customer facing TV or Android box setting issue', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'লাল বাতি জ্বলে / LOS Red Light', 'description' => 'LOS light blinking red (ONU signal loss)', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'অনু বন্ধ / ONU Offline (Power issue)', 'description' => 'ONU is turned off or has power issues', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'স্লো ইন্টারনেট / Slow Speed', 'description' => 'Internet speed is slow', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'লাইন কাটা / Cable Cut', 'description' => 'Physical cable damage or cut', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'রাউটার রিসেট / Router Configuration', 'description' => 'Router reset or needs re-configuration', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'কানেকশন ড্রপ / Connection Drop', 'description' => 'Internet connection dropping frequently', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ওয়াইফাই পাসওয়ার্ড পরিবর্তন / Wifi Password Change', 'description' => 'Customer wants to change wifi password', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'বিলিং বা পেমেন্ট সমস্যা / Billing & Payment', 'description' => 'Invoice or payment issues', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PPPoE লগইন সমস্যা / PPPoE Login Fail', 'description' => 'Unable to connect to PPPoE server', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'নতুন কানেকশন অনুরোধ / New Connection Request', 'description' => 'Request for new internet link', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Slow Internet', 'description' => 'Customer facing slow speed issue.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Connection Drop', 'description' => 'Frequent disconnection problem.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Billing Issue', 'description' => 'Invoice or payment related problem.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Router Problem', 'description' => 'Router malfunction or configuration issue.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'No Internet', 'description' => 'Completely no connectivity reported.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fiber Cut', 'description' => 'Physical fiber cable cut or damaged.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Login Issue', 'description' => 'Unable to login to hotspot or PPPoE.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Low Signal', 'description' => 'WiFi signal strength is weak.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Power Issue', 'description' => 'Power adapter or power line problem.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cable Problem', 'description' => 'LAN or fiber cable not connected or faulty.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'IP Conflict', 'description' => 'Multiple clients using the same IP address.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'MAC Blocked', 'description' => 'Client MAC address blocked in MikroTik.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Account Expired', 'description' => 'Client account validity expired.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'DNS Problem', 'description' => 'DNS not resolving or misconfigured.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Device Overheat', 'description' => 'Router or ONU overheating issue.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Frequent Restart', 'description' => 'Router or ONU keeps rebooting frequently.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'High Ping', 'description' => 'Customer reporting high latency/ping.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Packet Loss', 'description' => 'Packet drop or unstable connection.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Port Issue', 'description' => 'ONU/OLT port down or not active.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bandwidth Mismatch', 'description' => 'Assigned speed not matching actual speed.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PPPoE Server Down', 'description' => 'PPP server or radius not responding.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hotspot Not Redirecting', 'description' => 'Hotspot login page not showing.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ONU Not Registered', 'description' => 'ONU/ONT not showing in OLT.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Signal Loss', 'description' => 'Optical signal loss detected.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Switch Problem', 'description' => 'Network switch not forwarding packets.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hardware Burn', 'description' => 'ONU/Router/Adapter physically burnt.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Configuration Error', 'description' => 'Wrong configuration on client or router.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Unauthorized User', 'description' => 'User using service without permission.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Customer Request', 'description' => 'General request for package or upgrade.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maintenance Work', 'description' => 'Temporary downtime for maintenance.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
