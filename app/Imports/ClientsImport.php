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

    protected string $isp_code;

    protected array $ispConfig = [

        'carnival' => [
            'heading_row'     => 1,
            'username_keys'   => ['carnival_id', 'username', 'customer_id', 'customerid', 'cust_id', 'client_id', 'clientid', 'clients_id', 'clientsid', 'id'],
            'contact_keys'    => ['mobile', 'mobile_no', 'mobile_number', 'phone', 'phone_number', 'contact'],
            'expiration_keys' => ['expiration', 'exp_date', 'expiration_date', 'expire_date', 'expiry', 'expiry_date', 'expiry_date_time', 'validity', 'validity_date', 'valid_until', 'valid_till', 'valid_to', 'exp_time'],
            'status_key'      => 'status',
            'name_key'        => 'name',
            'email_key'       => 'email',
            'package_key'     => 'package',
            'area_key'        => 'area',
            'address_key'     => 'address',
        ],

        'bijoy' => [
            'heading_row'     => 2,
            'username_keys'   => ['username', 'customer_id', 'customerid', 'cust_id', 'client_id', 'clientid', 'clients_id', 'clientsid', 'id', 'carnival_id'],
            'contact_keys'    => ['mobile', 'mobile_no', 'mobile_number', 'phone', 'phone_number', 'contact'],
            'expiration_keys' => ['exp_date', 'expiration', 'expiration_date', 'expire_date', 'expiry', 'expiry_date', 'expiry_date_time', 'validity', 'validity_date', 'valid_until', 'valid_till', 'valid_to', 'exp_time'],
            'status_key'      => 'status',
            'name_key'        => 'name',
            'email_key'       => 'email',
            'package_key'     => 'package',
            'area_key'        => 'area',
            'address_key'     => null,
        ],

        'icc' => [
            'heading_row'     => 2,
            'username_keys'   => ['username', 'customer_id', 'customerid', 'cust_id', 'client_id', 'clientid', 'clients_id', 'clientsid', 'id', 'carnival_id'],
            'contact_keys'    => ['mobile', 'mobile_no', 'mobile_number', 'phone', 'phone_number', 'contact'],
            'expiration_keys' => ['exp_date', 'expiration', 'expiration_date', 'expire_date', 'expiry', 'expiry_date', 'expiry_date_time', 'validity', 'validity_date', 'valid_until', 'valid_till', 'valid_to', 'exp_time'],
            'status_key'      => 'status',
            'name_key'        => 'name',
            'email_key'       => 'email',
            'package_key'     => 'package',
            'area_key'        => 'area',
            'address_key'     => null,
        ],
    ];

    public function __construct(string $isp_code)
    {
        $this->isp_code = strtolower(trim($isp_code));
    }

    public function headingRow(): int
    {
        return $this->ispConfig[$this->isp_code]['heading_row'] ?? 1;
    }

    public function collection(Collection $rows)
    {
        $config = $this->ispConfig[$this->isp_code] ?? null;

        if (!$config) {
            Session::push('import_errors', "Unknown ISP code: {$this->isp_code}");
            return;
        }

        foreach ($rows as $row) {

            // 1. Username resolve — skip if empty
            $username = $this->resolveField($row, $config['username_keys']);
            if (empty($username)) continue;
            $username = trim((string) $username);

            // 2. Expiration
            $rawExpiration = $this->resolveExpiration($row, $config['expiration_keys']);
            $expiration = $this->parseExpiration($rawExpiration);

            // DEBUG: Log missing expiration for active clients
            if (empty($expiration) && !empty($rawExpiration)) {
                // Value exists in file but couldn't be parsed
                \Log::warning("[{$this->isp_code}] Failed to parse expiration for {$username}: {$rawExpiration}");
            }

            // 3. Status
            $rawStatus = (string) $this->resolveField($row, [
                $config['status_key'], 'client_status', 'account_status', 'connection_status',
            ]);
            $status = $this->normalizeStatus($rawStatus);

            // 4. Address
            $address = $this->resolveAddress($row, $config);

            // 5. New values — isp_code INCLUDED so it updates on change
            $newValues = $this->onlyPresentValues([
                'isp_code'   => $this->isp_code,  // ← last imported ISP জিতবে
                'name'       => $this->resolveField($row, [$config['name_key'], 'client_name', 'customer_name', 'full_name']),
                'contact'    => $this->resolveField($row, $config['contact_keys']),
                'secondary_contact' => $this->resolveField($row, ['secondary_contact', 'alternate_contact', 'alternate_mobile']),
                'email'      => $this->resolveField($row, [$config['email_key'], 'email_address']),
                'address'    => $address,
                'package'    => $this->resolveField($row, [$config['package_key'], 'package_name', 'service_package', 'plan']),
                'password'   => $this->resolveField($row, ['password', 'pppoe_password']),
                'Onu_mac'    => $this->resolveField($row, ['onu_mac', 'onu_mac_address', 'mac_address']),
                'onu_serial' => $this->resolveField($row, ['onu_serial', 'onu_serial_number']),
                'onu_brand'  => $this->resolveField($row, ['onu_brand']),
                'cable'      => $this->resolveField($row, ['cable', 'cable_meter', 'cable_length']),
                'billing_type' => $this->resolveField($row, ['billing_type']),
                'gps_location' => $this->resolveField($row, ['gps_location', 'location']),
                'comment'    => $this->resolveField($row, ['comment', 'remarks', 'note']),
                'expiration' => $expiration,
                'status'     => trim($rawStatus) === '' ? null : $status,
            ]);

            // The selected ISP and the resolved username are the record key;
            // imports must never replace either key on an existing client.
            unset($newValues['isp_code']);

            try {
                // 6. username ONLY দিয়ে find — isp_code দিয়ে না
                $existing = Client::where('isp_code', $this->isp_code)
                    ->where('username', $username)
                    ->first();

                if ($existing) {
                    // A blank or unparsable cell must never destroy stored data.
                    // Expiration/status are kept as-is when the sheet has no usable value.
                    if ($rawExpiration !== null && $rawExpiration !== '' && $expiration === null) {
                        unset($newValues['expiration']);
                        \Log::info("[{$this->isp_code}] Kept existing expiration for {$username}: " . $existing->expiration);
                    }

                    // 7. কী কী চেঞ্জ হয়েছে detect করো
                    $changes = $this->detectChanges($existing, $newValues);

                    if (!empty($changes)) {
                        $previousIspCode = $existing->isp_code;
                        $existing->update($newValues);

                        foreach ($changes as $field => $change) {
                            Session::push('import_changes', [
                                'isp_code'     => $this->isp_code,
                                'old_isp_code' => $previousIspCode,
                                'username'     => $username,
                                'field'        => $field,
                                'old'          => $change['old'],
                                'new'          => $change['new'],
                            ]);
                        }
                    } else {
                        // কোনো change নেই — log করো
                        Session::push('import_skipped',
                            "[{$this->isp_code}] {$username}: no changes detected"
                        );
                    }

                } else {
                    // 8. নতুন client
                    Client::create(array_merge([
                        'isp_code' => $this->isp_code,
                        'username' => $username,
                        'name'     => $newValues['name'] ?? $username,
                        'status'   => $newValues['status'] ?? 'Active',
                    ], $newValues));

                    Session::push('import_new', [
                        'isp_code' => $this->isp_code,
                        'username' => $username,
                    ]);
                }

            } catch (\Exception $e) {
                Session::push('import_errors',
                    "[{$this->isp_code}] {$username}: " . $e->getMessage()
                );
            }
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Changed fields detect করো।
     * isp_code change হলে সেটাও ধরবে।
     */
    protected function detectChanges(Client $existing, array $newValues): array
    {
        $changes = [];

        foreach ($newValues as $field => $newValue) {

            $oldValue = $existing->{$field};

            // Expiration — date only compare
            if ($field === 'expiration') {
                $oldNorm = $oldValue ? Carbon::parse($oldValue)->format('Y-m-d') : null;
                $newNorm = $newValue ? Carbon::parse($newValue)->format('Y-m-d') : null;

                if ($oldNorm !== $newNorm) {
                    $changes[$field] = ['old' => $oldNorm, 'new' => $newNorm];
                }
                continue;
            }

            // বাকি সব — trimmed string compare
            $oldNorm = trim((string)($oldValue ?? ''));
            $newNorm = trim((string)($newValue ?? ''));

            if ($oldNorm !== $newNorm) {
                $changes[$field] = ['old' => $oldValue, 'new' => $newValue];
            }
        }

        return $changes;
    }

    protected function resolveField($row, array $keys): mixed
    {
        foreach ($keys as $key) {
            $val = $row[$key] ?? null;
            if ($val !== null && $val !== '') return $val;
        }
        return null;
    }

    /**
     * HeadingRow normalises "Expiry Date" to "expiry_date". The fallback
     * also supports provider-specific labels such as "Valid Till".
     */
    protected function resolveExpiration($row, array $keys): mixed
    {
        $value = $this->resolveField($row, $keys);

        if ($value !== null && $value !== '') {
            return $value;
        }

        foreach ($row as $heading => $candidate) {
            $heading = strtolower((string) $heading);

            if (preg_match('/(?:expiration|expiry|expire|validity|valid_until|valid_till|valid_to)/', $heading)
                && $candidate !== null && $candidate !== '') {
                return $candidate;
            }
        }

        return null;
    }

    /** Keep blank cells from erasing saved client data during partial imports. */
    protected function onlyPresentValues(array $values): array
    {
        return array_filter($values, static function ($value) {
            return $value !== null && $value !== '';
        });
    }

    protected function resolveAddress($row, array $config): ?string
    {
        if (!empty($config['address_key'])) {
            return $this->resolveField($row, [
                $config['address_key'], 'full_address', 'client_address', 'customer_address',
            ]);
        }

        $parts = array_filter([
            $row['flat_level'] ?? null,
            $row['house']      ?? null,
            $row['road']       ?? null,
            $row[$config['area_key']] ?? null,
        ]);

        return !empty($parts) ? implode(', ', $parts) : null;
    }

    protected function parseExpiration(mixed $value): ?\DateTime
    {
        if (empty($value)) return null;

        // Trim whitespace
        $value = is_string($value) ? trim($value) : $value;
        if (empty($value) || in_array(strtolower((string)$value), ['-', 'n/a', 'null', '0000-00-00', '0000-00-00 00:00:00'])) {
            return null;
        }

        try {
            // Depending on the spreadsheet cell format, Laravel Excel may
            // already hydrate the date as a DateTime object.
            if ($value instanceof \DateTimeInterface) {
                return Carbon::instance($value);
            }

            if (is_numeric($value)) {
                // Excel numeric date format
                return Date::excelToDateTimeObject($value);
            } elseif (is_string($value)) {
                // Bijoy/ICC may repeat the ISO date in one cell, e.g.
                // "2026-11-02 2026-11-02". The database stores a date, so
                // the first ISO date is the authoritative expiration value.
                if (in_array($this->isp_code, ['bijoy', 'icc'], true)
                    && preg_match('/^\s*(\d{4}[-\/.]\d{1,2}[-\/.]\d{1,2})/', $value, $matches)) {
                    $value = $matches[1];
                }

                // Normalize dots/slashes to hyphens for standard format checking
                $normalized = str_replace(['/', '.'], '-', $value);
                $parsed = null;

                // Carnival exports month/day/year, while Bijoy and ICC export
                // ISO year-month-day. Parsing Carnival first avoids treating
                // 11/12/2025 as 11 December instead of 12 November.
                $formats = $this->isp_code === 'carnival' ? [
                    'm/d/Y G:i',
                    'm/d/Y H:i',
                    'm/d/Y g:i A',
                    'm-d-Y G:i',
                    'm-d-Y H:i',
                    'm-d-Y g:i A',
                    'm/d/Y',
                    'm-d-Y',
                ] : [];

                $formats = array_merge($formats, [
                    'Y-m-d H:i:s',
                    'Y-m-d H:i',
                    'Y-m-d',
                    'd-m-Y',
                    'm-d-Y',
                    'Y-m-d H:i:s',
                    'd-m-Y H:i:s',
                    'm-d-Y H:i:s',
                    'd/m/Y',
                    'm/d/Y',
                    'Y/m/d',
                ]);

                foreach ([$value, $normalized] as $valToTest) {
                    foreach ($formats as $format) {
                        try {
                            $parsed = \DateTime::createFromFormat($format, $valToTest);
                            if ($parsed && $parsed->format('Y') > 1970) break 2;
                        } catch (\Exception) {
                            continue;
                        }
                    }
                }

                // Fallback to Carbon::parse if specific formats didn't work
                if (!$parsed || $parsed->format('Y') <= 1970) {
                    $parsed = Carbon::parse($value);
                }

                return $parsed instanceof \DateTime ? $parsed : $parsed->toDateTime();
            }
        } catch (\Exception $e) {
            \Log::warning("Failed to parse expiration date: {$value} - " . $e->getMessage());
            return null;
        }

        return null;
    }

    protected function normalizeStatus(string $raw): string
    {
        return match (strtolower(trim($raw))) {
            'active', 'registered', 'enabled', 'paid' => 'Active',
            'expired', 'inactive', 'unpaid'            => 'Expired',
            'suspended', 'blocked', 'disabled'         => 'Suspended',
            default                                    => 'Active',
        };
    }
}
