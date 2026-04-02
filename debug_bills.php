<?php
require 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

app()->make('Illuminate\Database\ConnectionResolverInterface');

// Get client with username 1000665
$client = DB::table('clients')->where('username', '1000665')->first();
echo "Client: ";
if ($client) {
    echo "Found! ID={$client->id}, Name={$client->name}, Username={$client->username}\n";

    // Get bills for this client
    $bills = DB::table('due_bills')->where('client_id', $client->id)->get();
    echo "Bills for client {$client->id}: " . count($bills) . "\n";
    if (count($bills) > 0) {
        foreach ($bills as $bill) {
            echo "  - Bill #{$bill->id}: {$bill->month}/{$bill->year}, Amount={$bill->amount}, Status={$bill->status}\n";
        }
    }
} else {
    echo "Not found\n";
}

// Check total bills in database
echo "\nTotal bills in database: " . DB::table('due_bills')->count() . "\n";

// Sample of unpaid bills
$unpaid = DB::table('due_bills')->whereIn('status', ['unpaid', 'partially_paid'])->limit(5)->get();
echo "Sample unpaid bills:\n";
foreach ($unpaid as $bill) {
    echo "  - Bill #{$bill->id}, Client ID={$bill->client_id}, Status={$bill->status}\n";
}
