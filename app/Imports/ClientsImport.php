<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts; // WithUpserts ইমপোর্ট করা হয়েছে
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Carbon;

class ClientsImport implements ToModel, WithHeadingRow, WithProgressBar, WithUpserts
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // row এর key data check করা হচ্ছে
        if (!isset($row['name']) || !isset($row['mobile'])) {
            return null;
        }

        // Excel-এর তারিখ থেকে DateTime Object-এ convert করা হচ্ছে
        $expiration = null;
        if (isset($row['expiration'])) {
            if (is_numeric($row['expiration'])) {
                $expiration = Date::excelToDateTimeObject($row['expiration']);
            } else {
                try {
                    $expiration = Carbon::parse($row['expiration']);
                } catch (\Exception $e) {
                    $expiration = null;
                }
            }
        }

        // ক্লায়েন্ট মডেল-এর নতুন instance return করা হচ্ছে।
        return new Client([
            "username"      => $row['carnival_id'] ?? null,
            "name"          => $row['name'],
            "address"       => $row['address'] ?? null,
            "contact"       => $row['mobile'],
            "email"         => $row['email'] ?? null,
            "package"       => $row['package'] ?? null,
            "package_price" => $row['package_price'] ?? null,
            "expiration"    => $expiration,
            "status"        => $row['status'] ?? null,
        ]);
    }

    /**
     * কোন কলামটি upsert করার জন্য ব্যবহার করা হবে, তা Maatwebsite\Excel-কে বলে দেওয়া হচ্ছে।
     * এখানে 'username' ডাটাবেস কলামটি ব্যবহার করা হয়েছে।
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'username';
    }
}
