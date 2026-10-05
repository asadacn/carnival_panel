<?php

namespace App\Imports;

use App\Models\CardSeller;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CardSellerImport implements ToCollection, WithHeadingRow
{
    /** @var int newly created rows */
    public int $inserted = 0;

    /** @var int rows updated (matched by contact number) */
    public int $updated = 0;

    /** @var array skipped row messages */
    public array $skipped = [];

    /** @var array per-row error messages */
    public array $errors = [];

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $row  = $row->toArray();
            $line = $index + 2; // heading row is 1, data starts at 2

            try {
                $name    = $this->get($row, $this->nameKeys());
                $contact = $this->normalizeContact($this->get($row, $this->contactKeys()));
                $shop    = $this->get($row, $this->shopKeys());

                // fully empty row — just skip silently
                if (empty($name) && empty($contact) && empty($shop)) {
                    continue;
                }

                // no recognizable name header at all — fall back to the
                // first non-empty text cell of the row (SL/contact cells
                // are numeric, so the retailer name wins in the sample layout)
                if (empty($name) && !$this->anyKeyExists($row, $this->nameKeys())) {
                    $name = $this->firstTextCell($row);
                }

                // name is a required column — never insert null
                if (empty($name)) {
                    $this->skipped[] = "Row {$line}: retailer name is empty";
                    continue;
                }

                $data = [
                    'name'        => $name,
                    'contact'     => substr($contact, 0, 12),
                    'store_title' => $shop,
                    'address'     => $this->buildAddress($row),
                ];

                // update the existing seller when the contact number matches
                $existing = $contact !== ''
                    ? CardSeller::where('contact', substr($contact, 0, 12))->first()
                    : null;

                if ($existing) {
                    $existing->update($data);
                    $this->updated++;
                } else {
                    CardSeller::create($data);
                    $this->inserted++;
                }
            } catch (\Exception $e) {
                $this->errors[] = "Row {$line}: " . $e->getMessage();
            }
        }
    }

    private function nameKeys(): array
    {
        return [
            'retailer name', 'retailer_name', 'retailername', 'retailers name', 'retailersname',
            "retailer's name", "retailer'sname",
            'retailer',
            'name', 'seller', 'seller name', 'seller_name', 'sellername',
            'owner', 'owner name', 'owner_name', 'ownername',
            'person name', 'person_name', 'personname',
            'customer name', 'customer_name', 'customername',
            'client name', 'client_name', 'clientname',
            'নাম', 'বিক্রেতা', 'বিক্রেতার নাম', 'বিক্রেতা নাম', 'মালিক', 'রিটেইলার', 'রিটেইলারের নাম',
        ];
    }

    private function contactKeys(): array
    {
        return [
            'contact number', 'contact_number', 'contactnumber',
            'contact', 'contact no', 'contact_no', 'contactno',
            'mobile', 'mobile number', 'mobile_number', 'mobilenumber',
            'phone', 'phone number', 'phone_number', 'phonenumber',
            'যোগাযোগ', 'মোবাইল', 'মোবাইল নম্বর', 'ফোন', 'ফোন নম্বর', 'যোগাযোগ নম্বর',
        ];
    }

    private function shopKeys(): array
    {
        return [
            'shop name', 'shop_name', 'shopname',
            'store title', 'store_title', 'storetitle',
            'store', 'shop', 'দোকানের নাম', 'দোকান', 'স্টোর',
        ];
    }

    /**
     * Combine shop address + village, union, upzila/area, district, division
     * into the single address column.
     */
    private function buildAddress(array $row): ?string
    {
        $parts = [];

        $shopAddress = $this->get($row, [
            'shop address', 'shop_address', 'shopaddress',
            'address', 'ঠিকানা', 'দোকানের ঠিকানা',
        ]);
        if (!empty($shopAddress)) {
            $parts[] = $shopAddress;
        }

        foreach ([
            'village', 'village name', 'village_name', 'গ্রাম',
            'union', 'union name', 'union_name', 'ইউনিয়ন', 'ইউনিয়ন পরিষদ',
            'upzila/area', 'upazila/area', 'upzila area', 'upazila area',
            'upzila', 'upazila', 'area', 'উপজেলা', 'উপজেলা/এলাকা', 'থানা', 'অঞ্চল',
            'district', 'district name', 'district_name', 'জেলা', 'জিলা',
            'division', 'division name', 'division_name', 'বিভাগ',
        ] as $key) {
            $part = $this->get($row, [$key]);
            if (!empty($part)) {
                $parts[] = $part;
            }
        }

        return $parts ? implode(', ', $parts) : null;
    }

    /**
     * Check whether any of the given header keys exist in the row.
     */
    private function anyKeyExists(array $row, array $keys): bool
    {
        $normalizeKey = static function (string $key): string {
            $key = preg_replace('/\x{FEFF}/u', '', $key);
            $key = mb_strtolower(trim($key));

            return preg_replace('/[\s\-\.\/_\']+/u', '', $key);
        };

        $normalized = [];
        foreach (array_keys($row) as $k) {
            $normalized[$normalizeKey((string) $k)] = true;
        }

        foreach ($keys as $key) {
            if (isset($normalized[$normalizeKey($key)])) {
                return true;
            }
        }

        return false;
    }

    /**
     * First non-empty, non-numeric cell value — used as a
     * fallback for the retailer name column.
     */
    private function firstTextCell(array $row)
    {
        foreach ($row as $v) {
            if ($v === null || $v === '') {
                continue;
            }
            if (is_int($v) || is_float($v) || is_numeric((string) $v)) {
                continue;
            }
            return trim((string) $v);
        }

        return null;
    }

    /**
     * Resolve a value from the row trying several header key variations.
     * Handles BOM, case and separator (space/dash/dot/slash/underscore/
     * apostrophe) differences in the heading row.
     */
    private function get(array $row, array $keys)
    {
        $normalizeKey = static function (string $key): string {
            $key = preg_replace('/\x{FEFF}/u', '', $key);
            $key = mb_strtolower(trim($key));

            // strip every separator so "upzila_area", "upzila/area" and
            // "upzilaarea" all match
            return preg_replace('/[\s\-\.\/_\']+/u', '', $key);
        };

        $normalized = [];
        foreach ($row as $k => $v) {
            $normalized[$normalizeKey((string) $k)] = is_string($v) ? trim($v) : $v;
        }

        foreach ($keys as $key) {
            $ck = $normalizeKey($key);
            if (array_key_exists($ck, $normalized)) {
                $val = $normalized[$ck];
                if ($val === '' || $val === null) {
                    return null;
                }
                return is_string($val) ? trim($val) : $val;
            }
        }

        return null;
    }

    /**
     * Excel may hand over phone numbers as int/float — always return a clean string.
     */
    private function normalizeContact($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            return rtrim(rtrim(number_format($value, 10, '.', ''), '0'), '.');
        }

        $value = trim((string) $value);

        return preg_replace('/[\s\-]/', '', $value);
    }
}
