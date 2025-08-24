<?php

namespace App\Imports;

use App\Models\HotspotZone;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HotspotZoneImport implements ToModel, WithHeadingRow
{
    // Header সবসময় 1 নম্বর রো থেকে ধরবে
    public function headingRow(): int
    {
        return 1;
    }

    // এক জায়গায় সব normalization করে নিই
    private function get(array $row, string $key)
    {
        // লক্ষ্য key-এর বিভিন্ন সম্ভাব্য ভ্যারিয়েশন
        $variants = [
            $key,                                   // zone_id
            str_replace('_', ' ', $key),           // zone id
            strtoupper($key),                      // ZONE_ID
            ucwords(str_replace('_', ' ', $key)),  // Zone Id
        ];

        // row-এর key গুলো normalize করে ম্যাপ তৈরি
        $normalized = [];
        foreach ($row as $k => $v) {
            // BOM (﻿\ufeff) ও extra space রিমুভ + lowercase
            $cleanK = preg_replace('/\x{FEFF}/u', '', $k); // BOM remove
            $cleanK = trim($cleanK);
            $cleanK = mb_strtolower($cleanK);
            $normalized[$cleanK] = is_string($v) ? trim($v) : $v;
        }

        // সম্ভাব্য ভ্যারিয়েশনগুলো থেকে match করি
        foreach ($variants as $cand) {
            $ck = mb_strtolower(trim(preg_replace('/\x{FEFF}/u', '', $cand)));
            if (array_key_exists($ck, $normalized)) {
                $val = $normalized[$ck];
                return ($val === '') ? null : $val;
            }
        }

        // শেষ চেষ্টা: underscore ↔ space swap করে exact compare
        $alt1 = mb_strtolower(trim($key));
        $alt2 = mb_strtolower(trim(str_replace('_', ' ', $key)));
        foreach ($normalized as $nk => $val) {
            if ($nk === $alt1 || $nk === $alt2) {
                $val = is_string($val) ? trim($val) : $val;
                return ($val === '') ? null : $val;
            }
        }

        return null;
    }

    public function model(array $row)
    {
        // সব ভ্যালু সেফলি পড়ি
        $zoneId     = $this->get($row, 'zone_id');
        $zoneTitle  = $this->get($row, 'zone_title');
        $onuMac     = $this->get($row, 'onu_mac');
        $onuBrand   = $this->get($row, 'onu_brand');
        $uspAdapter = $this->get($row, 'usp_adapter');

        // প্রয়োজন হলে সংখ্যা কনভার্সন (ঐচ্ছিক)
        if (is_string($zoneId)) {
            $zoneId = trim($zoneId);
            // pure number না হলে null
            if ($zoneId === '' || !ctype_digit($zoneId)) {
                $zoneId = null;
            } else {
                $zoneId = (int)$zoneId;
            }
        }

        return new HotspotZone([
            'zone_id'     => $zoneId,
            'zone_title'  => $zoneTitle,
            'onu_mac'     => $onuMac,
            'onu_brand'   => $onuBrand,
            'usp_adapter' => $uspAdapter,
        ]);
    }
}
