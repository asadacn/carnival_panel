<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$complainTypes = \App\Models\ComplainType::pluck('name')->toArray();
echo "Complain Types in Database:\n";
print_r($complainTypes);
