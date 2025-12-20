<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Session;

class ClientsImport implements ToCollection, WithHeadingRow, WithProgressBar
{
    use Importable;

    protected $isp_code;

    public function __construct($isp_code)
    {
        $this->isp_code = $isp_code;
    }

    /**
     * ডাইনামিক হেডার রো সেট করা
     * Carnival-এর জন্য ১ নম্বর রো, Bijoy-এর জন্য ২ নম্বর রো
     */
    public function headingRow(): int
    {
        return $this->isp_code === 'carnival' ? 1 : 2;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // ১. স্মার্ট ইউজারনেম ডিটেকশন
            // Carnival-এর ফাইলে কলামের নাম 'carnival_id', Bijoy-তে 'username'
            $username = $this->isp_code === 'carnival'
                ? ($row['carnival_id'] ?? $row['id'] ?? null)
                : ($row['username'] ?? $row['cust_id'] ?? null);

            if (empty($username)) {
                continue;
            }

            $username = trim((string)$username);

            // ২. এক্সপায়ার ডেট হ্যান্ডেল করা (Carnival: expiration, Bijoy: exp_date)
            $expValue = $row['expiration'] ?? $row['exp_date'] ?? null;
            $expiration = null;

            if (!empty($expValue)) {
                try {
                    $expiration = is_numeric($expValue)
                        ? Date::excelToDateTimeObject($expValue)
                        : Carbon::parse($expValue);
                } catch (\Exception $e) {
                    $expiration = null;
                }
            }

            // ৩. স্ট্যাটাস ম্যাপিং (Registered/Active -> Active)
            $rawStatus = strtolower(trim($row['status'] ?? ''));
            if ($rawStatus === 'active' || $rawStatus === 'registered') {
                $finalStatus = 'Active';
            } elseif ($rawStatus === 'expired') {
                $finalStatus = 'Expired';
            } else {
                $finalStatus = ucfirst($rawStatus ?: 'Active');
            }

            // ৪. ডাটাবেস আপডেট লজিক
            // নোট: Client মডেলে অবশ্যই 'isp_code' ফিল্ডটি $fillable এ থাকতে হবে
            $values = [
                'name'       => $row['name'] ?? null,
                'contact'    => $row['mobile'] ?? $row['contact'] ?? null,
                'package'    => $row['package'] ?? null,
                'expiration' => $expiration,
                'status'     => $finalStatus,
                'isp_code'   => $this->isp_code, // এটি Carnival এর ডাটাকে Bijoy-তে আপডেট করবে
            ];

            try {
                // updateOrCreate ইউজারনেম মিলে গেলে ডাটা আপডেট করবে
                Client::updateOrCreate(
                    ['username' => $username],
                    $values
                );
            } catch (\Exception $e) {
                Session::push('debug_logs', "Error for {$username}: " . $e->getMessage());
            }
        }
    }
}
