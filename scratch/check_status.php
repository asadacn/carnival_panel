<?php
use Illuminate\Support\Facades\DB;
use App\Models\Client;

// Check all records including soft-deleted
echo "=== All distinct status values (including soft-deleted) ===" . PHP_EOL;
$statuses = DB::table('clients')->distinct()->pluck('status');
foreach ($statuses as $s) {
    $all   = DB::table('clients')->where('status', $s)->count();
    $alive = DB::table('clients')->whereNull('deleted_at')->where('status', $s)->count();
    $soft  = $all - $alive;
    echo "  '$s' => $alive active | $soft soft-deleted | $all total" . PHP_EOL;
}

// Total counts
echo PHP_EOL . "=== Total client counts ===" . PHP_EOL;
echo "  Total (including soft-deleted): " . DB::table('clients')->count() . PHP_EOL;
echo "  Total active (not deleted):     " . Client::count() . PHP_EOL;
echo "  Soft-deleted:                   " . Client::onlyTrashed()->count() . PHP_EOL;
