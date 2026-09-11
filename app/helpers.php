<?php
//REPORT HEADER

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

if (!function_exists('report_header')) {
    function report_header($title)
    {
        return json_encode(view('layouts.report-header', ['title' => $title])->render());
    }
}



if (!function_exists('sms')) {
function sms($contacts, $message, $type = 'unicode')
{
    $api_key  = trim((string) config('services.mram.api_key'));
    $senderid = trim((string) config('services.mram.sender_id'));

    // -----------------------------
    // Number normalization
    // -----------------------------
    if ($contacts instanceof \Illuminate\Support\Collection) {
        $contacts = $contacts->toArray();
    }

    if (is_string($contacts)) {
        $contacts = [$contacts];
    }

    // প্রতিটি নাম্বারের আগে 88 যোগ করা
    $contacts = array_values(array_filter(array_map(function ($number) {
        $number = preg_replace('/\D/', '', (string) $number);

        if (str_starts_with($number, '01')) {
            $number = '88' . $number;
        } elseif (str_starts_with($number, '1')) {
            $number = '880' . $number;
        }

        return preg_match('/^8801\d{9}$/', $number) ? $number : null;
    }, $contacts)));

    if (!$api_key || !$senderid || !$contacts) {
        Log::error('SMS request rejected before API call', [
            'has_api_key' => (bool) $api_key,
            'has_senderid' => (bool) $senderid,
            'contact_count' => count($contacts),
        ]);
        return false;
    }

    // একসাথে join করা MRAM API অনুযায়ী
    $contacts = implode('+', $contacts);

    // -----------------------------
    // API call
    // -----------------------------
    try {
        $response = Http::timeout(20)->asForm()->post("https://sms.mram.com.bd/smsapi", [
            "api_key"  => $api_key,
            "type"     => $type,
            "contacts" => $contacts,
            "senderid" => $senderid,
            "msg"      => $message,
        ]);

        $body = $response->body();

        Log::info('SMS API response', [
            'contacts' => $contacts,
            'status' => $response->status(),
            'response' => $body,
        ]);

        // The provider returns a plain-text error instead of a reliable HTTP error code.
        if (stripos($body, 'error') !== false || stripos($body, 'failed') !== false || !$response->successful()) {
            Log::error('SMS API failed', [
                'contacts' => $contacts,
                'status'   => $response->status(),
                'response' => $body,
            ]);
            return false;
        }

        return true;

    } catch (\Exception $e) {
        Log::error('SMS exception: ' . $e->getMessage(), ['contacts' => $contacts]);
        return false;
    }
}}

if (!function_exists('sms_count')) {
    function sms_count($text)
    {
        $text = (string) $text;
        $charCount = mb_strlen($text, 'UTF-8');

        $gsm7bit = "@£\$¥èéùìòÇ\nØø\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà";
        $gsm7bitEx = "^{}\\[~]|€";

        $isGsm7bit = true;
        $isGsm7bitEx = true;
        $exCount = 0;

        for ($i = 0; $i < $charCount; $i++) {
            $char = mb_substr($text, $i, 1, 'UTF-8');
            if (strpos($gsm7bit, $char) === false) {
                $isGsm7bit = false;
            }
            if (strpos($gsm7bit . $gsm7bitEx, $char) === false) {
                $isGsm7bitEx = false;
            }
            if (strpos($gsm7bitEx, $char) !== false) {
                $exCount++;
            }
            if (!$isGsm7bit && !$isGsm7bitEx) {
                break;
            }
        }

        if ($isGsm7bit) {
            $encoding = 'GSM_7BIT';
            $perMessage = 160;
            $multiPerMessage = 153;
            $length = strlen($text);
        } elseif ($isGsm7bitEx) {
            $encoding = 'GSM_7BIT_EX';
            $perMessage = 160;
            $multiPerMessage = 153;
            $length = strlen($text) + $exCount;
        } else {
            $encoding = 'UTF16';
            $perMessage = 70;
            $multiPerMessage = 67;
            $length = $charCount;
        }

        if ($length > $perMessage) {
            $perMessage = $multiPerMessage;
        }

        $messages = $length == 0 ? 0 : (int) ceil($length / $perMessage);
        $remaining = $perMessage * $messages - $length;
        if ($remaining == 0 && $messages == 0) {
            $remaining = $perMessage;
        }

        return [
            'encoding' => $encoding,
            'length' => $length,
            'per_message' => $perMessage,
            'remaining' => $remaining,
            'messages' => $messages,
        ];
    }
}

if (!function_exists('logSms')) {
    /**
     * Send SMS and persist a log entry in the sms_logs table.
     *
     * @param string|array $contacts
     * @param string       $message
     * @param string       $type       'unicode' | 'text'
     * @param int|null     $clientId   Optional client id
     * @param string|null  $username   Optional client username (used as client_identifier)
     * @param string|null  $messageType 'single' | 'bulk' | 'bill' | 'bill_payment' | 'reminder'
     * @return bool
     */
    function logSms($contacts, $message, $type = 'unicode', $clientId = null, $username = null, $messageType = 'single')
    {
        $isSent = sms($contacts, $message, $type);

        try {
            $charCount = mb_strlen($message, 'UTF-8');
            $smsParts  = $charCount <= 70 ? 1 : (int) ceil($charCount / 67);

            $log = new \App\Models\SMSLOG();
            $log->client_id         = $clientId;
            $log->client_identifier = $username;
            $log->user_id           = auth()->id();
            $log->contact           = is_array($contacts) ? implode(',', $contacts) : $contacts;
            $log->sms               = $message;
            $log->character_count   = $charCount;
            $log->sms_count         = $smsParts;
            $log->message_type      = $messageType;
            $log->encoding          = $type;
            $log->gateway           = 'mram';
            $log->status            = $isSent ? '1' : '0';
            $log->error_message     = $isSent ? null : 'Gateway dispatch failed';
            $log->sent_at           = $isSent ? now() : null;
            $log->save();
        } catch (\Throwable $e) {
            Log::error('SMS log persistence failed: ' . $e->getMessage());
        }

        return $isSent;
    }
}

//SMS BALANCE CHECKER

if (!function_exists('sms_balance')) {
    function sms_balance()
    {
        return Cache::remember('sms_balance', 300, function () {
            $api_key = config('services.mram.api_key') ?? env('MRAM_API_KEY');
            Log::info('SMS balance check start', ['api_key_present' => !empty($api_key)]);

            if (!$api_key) {
                Log::warning('SMS balance: API key missing');
                return 'N/A';
            }

            $endpoints = [
                "https://sms.mram.com.bd/miscapi/{$api_key}/getBalance",
                "https://sms.mram.com.bd/miscapi/{$api_key}/balance",
                "https://sms.mram.com.bd/api/getBalance?api_key={$api_key}",
            ];

            foreach ($endpoints as $url) {
                try {
                    $response = Http::timeout(10)->get($url);
                    Log::info('SMS balance API response', ['url' => $url, 'status' => $response->status(), 'body' => $response->body()]);

                    if (!$response->successful()) {
                        continue;
                    }

                    $body = $response->body();

                    if (preg_match('/BDT\s*([\d,.]+)/', $body, $matches)) {
                        Log::info('SMS balance matched BDT', ['value' => $matches[1]]);
                        return $matches[1];
                    }

                    if (is_numeric(trim($body))) {
                        Log::info('SMS balance matched numeric', ['value' => trim($body)]);
                        return trim($body);
                    }
                } catch (\Exception $e) {
                    Log::warning('SMS balance API exception', ['url' => $url, 'error' => $e->getMessage()]);
                    continue;
                }
            }

            Log::warning('SMS balance: no valid response from any endpoint');
            return 'N/A';
        });
    }
}



//ENG TO BAN DIGIT CONVERTER
if (!function_exists('en2bnNumber')) {
function en2bnNumber($number){
    $replace_array= array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
    $search_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
    $en_number = str_replace($search_array, $replace_array,  $number);

    return $en_number;
}}


//BDT MONEY FORMATTER
if (!function_exists('takaFormat')) {
    function takaFormat($input){
                //CUSTOM FUNCTION TO GENERATE ##,##,###.##
                $dec = "";
                $pos = strpos($input, ".");
                if ($pos === false){
                    //no decimals
                } else {
                    //decimals
                    $dec = substr(round(substr($input,$pos),2),1);
                    $input = substr($input,0,$pos);
                }
                $num = substr($input,-3); //get the last 3 digits
                $input = substr($input,0, -3); //omit the last 3 digits already stored in $num
                while(strlen($input) > 0) //loop the process - further get digits 2 by 2
                {
                    $num = substr($input,-2).",".$num;
                    $input = substr($input,0,-2);
                }
                return $num . $dec;
            }
        }

if (!function_exists('bdtFormat')) {
    function bdtFormat($amount, $decimals = 2) {
        $amount = number_format((float) $amount, $decimals, '.', '');
        $parts = explode('.', $amount);
        $whole = $parts[0];
        $decimal = $parts[1] ?? '';

        $len = strlen($whole);
        if ($len <= 3) {
            return $whole . ($decimals > 0 ? '.' . $decimal : '');
        }

        $first3 = substr($whole, -3);
        $rest = substr($whole, 0, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);

        return $rest . ',' . $first3 . ($decimals > 0 ? '.' . $decimal : '');
    }
}

//SEND TELEGRAM MESSAGE
if(!function_exists('sendTelegram')){
    function sendTelegram($message, $chat_id = null){
        $chat_id = $chat_id ?? config('services.telegram.chat_id');
        $token = config('services.telegram.bot_token');
        $url = "https://api.telegram.org/bot$token/sendMessage";

        if (!$token || !$chat_id) {
            Log::warning('Telegram send skipped: missing bot token or chat ID');
            return false;
        }

        try {
            $response = Http::timeout(10)->post($url, [
                'chat_id' => $chat_id,
                'text' => $message,
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['ok'] ?? false)) {
                Log::info('Telegram message sent successfully', [
                    'chat_id' => $chat_id,
                    'message_length' => strlen($message),
                ]);
                return true;
            }

            Log::warning('Telegram send failed', [
                'chat_id' => $chat_id,
                'response' => $result,
                'status' => $response->status(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::warning("Telegram send failed: " . $e->getMessage());
            return false;
        }
    }
}

//VOICE BROADCAST CAMPAIGN SENDER USING ELIT CALL API

if (!function_exists('sendVoiceCampaign')) {
    /**
     * Send a voice broadcast campaign using Elit Call API
     *
     * @param  string  $title
     * @param  int     $broadcastId
     * @param  string  $sender
     * @param  array   $numbers
     * @return array
     */
    function sendVoiceCampaign($title, $broadcastId, $sender, array $numbers)
    {
        try {
            $apiKey  = config('services.elitcall.key') ?? env('ELITCALL_API_KEY');
            $baseUrl = rtrim(config('services.elitcall.base_url') ?? env('ELITCALL_BASE_URL', 'https://call.mram.com.bd'), '/');

            if (empty($apiKey)) {
                return [
                    'success' => false,
                    'error'   => ['detail' => 'ELITCALL_API_KEY is not configured in .env or services config.'],
                ];
            }

            // Maximum numbers per request according to Elit Call API is 1000
            $chunks = array_chunk($numbers, 1000);
            $totalChunks = count($chunks);
            $createdCampaigns = [];
            $totalCallsScheduled = 0;

            foreach ($chunks as $index => $chunkNumbers) {
                // If chunked into multiple requests, append part suffix to title
                $chunkTitle = $totalChunks > 1 ? "{$title} (Part " . ($index + 1) . ")" : $title;

                // Truncate title to 100 characters max
                $chunkTitle = mb_substr($chunkTitle, 0, 100);

                // Use Http retry for 503 / transient network errors
                $response = Http::retry(3, 200, function ($exception) {
                    return $exception instanceof \Illuminate\Http\Client\RequestException
                        && optional($exception->response)->status() === 503;
                })->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])->post("{$baseUrl}/api/send-broadcast-campaign", [
                    'title'        => $chunkTitle,
                    'broadcast_id' => (int)$broadcastId,
                    'sender'       => (string)$sender,
                    'numbers'      => array_values($chunkNumbers),
                ]);

                if ($response->successful() && $response->status() === 201) {
                    $json = $response->json();
                    $createdCampaigns[] = $json['campaign_id'] ?? null;
                    $totalCallsScheduled += $json['total_calls'] ?? count($chunkNumbers);
                } else {
                    return [
                        'success' => false,
                        'error'   => $response->json() ?? ['detail' => 'HTTP error code: ' . $response->status()],
                        'partial_campaigns' => $createdCampaigns,
                    ];
                }
            }

            return [
                'success'       => true,
                'data'          => [
                    'campaign_id' => count($createdCampaigns) === 1 ? $createdCampaigns[0] : $createdCampaigns,
                    'status'      => 'processing',
                    'total_calls' => $totalCallsScheduled,
                ],
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error'   => ['detail' => $e->getMessage()],
            ];
        }
    }
}

if (!function_exists('getVoiceCampaignDetails')) {
    /**
     * Get details of a voice broadcast campaign
     *
     * @param  int  $campaignId
     * @return array
     */
    function getVoiceCampaignDetails($campaignId)
    {
        try {
            $apiKey  = config('services.elitcall.key') ?? env('ELITCALL_API_KEY');
            $baseUrl = rtrim(config('services.elitcall.base_url') ?? env('ELITCALL_BASE_URL', 'https://call.mram.com.bd'), '/');

            if (empty($apiKey)) {
                return [
                    'success' => false,
                    'error'   => ['detail' => 'ELITCALL_API_KEY is not configured in .env or services config.'],
                ];
            }

            $response = Http::retry(3, 200)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->get("{$baseUrl}/api/campaign/{$campaignId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data'    => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error'   => $response->json() ?? ['detail' => 'HTTP error code: ' . $response->status()],
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error'   => ['detail' => $e->getMessage()],
            ];
        }
    }
}

if (!function_exists('isp_profile')) {
    /**
     * Get the ISP model instance for a specific isp_code or default ISP
     *
     * @param string|null $ispCode
     * @return \App\Models\Isp|null
     */
    function isp_profile($ispCode = null)
    {
        return \App\Models\Isp::forCode($ispCode);
    }
}

if (!function_exists('isp_setting')) {
    /**
     * Get an ISP setting value for a specific ISP code with fallback
     *
     * @param string $key
     * @param mixed $default
     * @param string|null $ispCode
     * @return mixed
     */
    function isp_setting($key, $default = null, $ispCode = null)
    {
        $profile = \App\Models\Isp::forCode($ispCode);
        if ($profile && isset($profile->{$key}) && $profile->{$key} !== null && $profile->{$key} !== '') {
            return $profile->{$key};
        }

        return \App\Models\IspSetting::get($key, $default);
    }
}

if (!function_exists('isp_logo')) {
    /**
     * Get ISP Logo URL for a specific ISP code with fallback
     *
     * @param string|null $ispCode
     * @return string
     */
    function isp_logo($ispCode = null)
    {
        $profile = \App\Models\Isp::forCode($ispCode);
        if ($profile && $profile->logo_url) {
            return $profile->logo_url;
        }

        return \App\Models\IspSetting::getLogoUrl();
    }
}

if (!function_exists('isp_favicon')) {
    /**
     * Get ISP Favicon URL for a specific ISP code with fallback
     *
     * @param string|null $ispCode
     * @return string
     */
    function isp_favicon($ispCode = null)
    {
        $profile = \App\Models\Isp::forCode($ispCode);
        if ($profile && $profile->favicon_url) {
            return $profile->favicon_url;
        }

        return \App\Models\IspSetting::getFaviconUrl();
    }
}

if (!function_exists('isp_name')) {
    /**
     * Get ISP Name for a specific ISP code with fallback
     *
     * @param string|null $ispCode
     * @param string $default
     * @return string
     */
    function isp_name($ispCode = null, $default = 'Carnival Internet')
    {
        $profile = \App\Models\Isp::forCode($ispCode);
        if ($profile && !empty($profile->isp_name)) {
            return $profile->isp_name;
        }

        return \App\Models\IspSetting::get('isp_name', $default);
    }
}
