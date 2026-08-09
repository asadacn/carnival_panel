<?php
//REPORT HEADER

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (!function_exists('report_header')) {
    function report_header($title)
    {
        return json_encode(view('layouts.report-header', ['title' => $title])->render());
    }
}



if (!function_exists('sms')) {
function sms($contacts, $message, $type = 'unicode')
{
    $api_key  = trim((string) env('MRAM_API_KEY'));
    $senderid = trim((string) env('MRAM_SENDER_ID'));

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

//SMS BALANCE CHECKER

if (!function_exists('sms_balance')) {
function sms_balance()
{
    $api_key = env('MRAM_API_KEY');
    $url = "https://sms.mram.com.bd/miscapi/{$api_key}/getBalance";

    try {
        $response = Http::get($url);
        $body = $response->body();

        // শুধু BDT এবং সংখ্যাটি বের করা
        if (preg_match('/BDT\s*([\d,.]+)/', $body, $matches)) {
            return  $matches[1]; // BDT 473.32 এর মতো রিটার্ন
        }

        return $body; // যদি match না হয়, পুরো response ফেরত
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}}



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

//SEND TELEGRAM MESSAGE
if(!function_exists('sendTelegram')){
    function sendTelegram($message, $chat_id = null){
        $chat_id = $chat_id ?? env('TELEGRAM_CHAT_ID');
        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot$token/sendMessage";

        $response = Http::post($url, [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);

        return $response->successful();
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
