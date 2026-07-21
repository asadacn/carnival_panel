<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$commonTypes = [
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
];

foreach ($commonTypes as $type) {
    \App\Models\ComplainType::firstOrCreate(
        ['name' => $type['name']],
        ['description' => $type['description']]
    );
}

echo "Successfully added realistic Bengali/English complain types to DB!\n";
