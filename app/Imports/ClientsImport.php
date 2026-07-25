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
            'username_keys'   => ['carnival_id'],
            'contact_keys'    => ['mobile'],
            'expiration_keys' => ['expiration', 'exp_date', 'expiration_date', 'expire_date', 'validity', 'validity_date', 'expiry', 'exp_time'],
            'status_key'      => 'status',
            'name_key'        => 'name',
            'email_key'       => 'email',
            'package_key'     => 'package',
            'area_key'        => 'area',
            'address_key'     => 'address',
        ],

        'bijoy' => [
            'heading_row'     => 2,
            'username_keys'   => ['username', 'cust_id'],
            'contact_keys'    => ['mobile'],
            'expiration_keys' => ['exp_date', 'expiration', 'expiration_date', 'expire_date', 'validity', 'validity_date', 'expiry', 'exp_time'],
            'status_key'      => 'status',
            'name_key'        => 'name',
            'email_key'       => 'email',
            'package_key'     => 'package',
            'area_key'        => 'area',
            'address_key'     => null,
        ],

        'icc' => [
            'heading_row'     => 2,
            'username_keys'   => ['username', 'cust_id'],
            'contact_keys'    => ['mobile'],
            'expiration_keys' => ['exp_date', 'expiration', 'expiration_date', 'expire_date', 'validity', 'validity_date', 'expiry', 'exp_time'],
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
            $rawExpiration = $this->resolveField($row, $config['expiration_keys']);
            $expiration = $this->parseExpiration($rawExpiration);

            // DEBUG: Log missing expiration for active clients
            if (empty($expiration) && !empty($rawExpiration)) {
                // Value exists in file but couldn't be parsed
                \Log::warning("[{$this->isp_code}] Failed to parse expiration for {$username}: {$rawExpiration}");
            }

            // 3. Status
            $status = $this->normalizeStatus((string)($row[$config['status_key']] ?? ''));

            // 4. Address
            $address = $this->resolveAddress($row, $config);

            // 5. New values — isp_code INCLUDED so it updates on change
            $newValues = [
                'isp_code'   => $this->isp_code,  // ← last imported ISP জিতবে
                'name'       => $row[$config['name_key']] ?? null,
                'contact'    => $this->resolveField($row, $config['contact_keys']),
                'email'      => $row[$config['email_key']] ?? null,
                'address'    => $address,
                'package'    => $row[$config['package_key']] ?? null,
                'expiration' => $expiration,
                'status'     => $status,
            ];

            try {
                // 6. username ONLY দিয়ে find — isp_code দিয়ে না
                $existing = Client::where('username', $username)->first();

                if ($existing) {
                    // 7. কী কী চেঞ্জ হয়েছে detect করো
                    $changes = $this->detectChanges($existing, $newValues);

                    if (!empty($changes)) {
                        $existing->update($newValues);

                        foreach ($changes as $field => $change) {
                            Session::push('import_changes', [
                                'isp_code'     => $this->isp_code,
                                'old_isp_code' => $existing->isp_code,
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
                    Client::create(array_merge(
                        ['username' => $username],
                        $newValues
                    ));

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

    protected function resolveAddress($row, array $config): ?string
    {
        if (!empty($config['address_key'])) {
            return $row[$config['address_key']] ?? null;
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
            if (is_numeric($value)) {
                // Excel numeric date format
                return Date::excelToDateTimeObject($value);
            } elseif (is_string($value)) {
                // Normalize dots/slashes to hyphens for standard format checking
                $normalized = str_replace(['/', '.'], '-', $value);
                $parsed = null;

                // Try common formats
                $formats = [
                    'Y-m-d',
                    'd-m-Y',
                    'm-d-Y',
                    'Y-m-d H:i:s',
                    'd-m-Y H:i:s',
                    'm-d-Y H:i:s',
                    'd/m/Y',
                    'm/d/Y',
                    'Y/m/d',
                ];

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
