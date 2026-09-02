<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $isps = ['carnival', 'bijoy', 'icc'];

        // Shared package definitions (title => price)
        $packageDefs = [
            '7 Mbps'    => 525,
            '10 Mbps'   => 699,
            '12 Mbps'   => 699,
            '15 Mbps'   => 1049,
            '20 Mbps'   => 1260,
            '20 Mbps_RP' => 1558,
            '15 Mbps_RP' => 1400,
            '10 Mbps_RP' => 998,
        ];

        // Default ISP price overrides (same package, different price for specific ISPs)
        $ispOverrides = [
            'bijoy' => [
                '7 Mbps'    => 550,
                '10 Mbps'   => 720,
                '12 Mbps'   => 720,
                '15 Mbps'   => 1100,
                '20 Mbps'   => 1320,
                '20 Mbps_RP' => 1600,
                '15 Mbps_RP' => 1500,
                '10 Mbps_RP' => 1050,
            ],
            'icc' => [
                '7 Mbps'    => 500,
                '10 Mbps'   => 680,
                '12 Mbps'   => 680,
                '15 Mbps'   => 1000,
                '20 Mbps'   => 1200,
                '20 Mbps_RP' => 1500,
                '15 Mbps_RP' => 1350,
                '10 Mbps_RP' => 950,
            ],
        ];

        foreach ($isps as $isp) {
            foreach ($packageDefs as $title => $basePrice) {
                $price = $ispOverrides[$isp][$title] ?? $basePrice;
                DB::table('packages')->insert([
                    'title' => $title,
                    'price' => $price,
                    'descriptoin' => sprintf('%s package for %s ISP', $title, ucfirst($isp)),
                    'isp_code' => $isp,
                ]);
            }
        }
    }
}
