<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplainTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('complain_types')->insert([
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
