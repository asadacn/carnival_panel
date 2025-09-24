<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HotspotClient;
use Carbon\Carbon;

class ExpiringHotspotClientsCommand extends Command
{
    protected $signature = 'clients:notify-expired';
    protected $description = 'Send Telegram messages to expired and expiring clients';

    public function handle()
    {
        // Expired clients
        // Expired Clients
        $expiredClients = HotspotClient::where('status', 'active')
            ->whereDate('expires_at', '<', Carbon::today())
            ->get();

        foreach ($expiredClients as $client) {
            $msg = "🚨 <b>Expired Client Alert</b>\n"
                 . "👤 Name: {$client->name}\n"
                 . "📞 Contact: {$client->contact}\n"
                 . "🏠 Address: {$client->adrress}\n"
                 . "🔌 ONU MAC: {$client->onu_mac}\n"
                 . "⏳ Expired At: " . ($client->expires_at ? $client->expires_at->format('d M Y') : '-') . "\n";

            sendTelegram($msg);
        }

        // Clients Expiring Tomorrow
        $tomorrow = Carbon::tomorrow();
        $expiringClients = HotspotClient::whereDate('expires_at', $tomorrow)->get();

        foreach ($expiringClients as $client) {
            $msg = "⏰ <b>Expiring Soon</b>\n"
                 . "Name: {$client->name}\n"
                 . "Contact: {$client->contact}\n"
                 . "Address: {$client->adrress}\n"
                 . "ONU MAC: {$client->onu_mac}\n"
                 . "Expires At: " . $client->expires_at->format('d M Y') . "\n";

            sendTelegram($msg);
        }

        $this->info("✅ Telegram notification sent successfully.");
        return 0;
    }
}
