<?php

namespace App\Console\Commands;

use App\Models\DueBill;
use Illuminate\Console\Command;

class SendDueBillRemindersCommand extends Command
{
    protected $signature = 'due-bills:send-reminders';

    protected $description = 'Send daily SMS reminders for unpaid and overdue bills';

    public function handle(): int
    {
        $sent = 0;

        DueBill::with('client')
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->where('due_date', '<=', today())
            ->chunkById(100, function ($bills) use (&$sent) {
                foreach ($bills as $bill) {
                    if ($bill->sendReminder()) {
                        $sent++;
                    }
                }
            });

        $this->info("Sent {$sent} due bill reminder(s).");
        return self::SUCCESS;
    }
}