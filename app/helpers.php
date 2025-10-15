<?php
//REPORT HEADER

use Illuminate\Support\Facades\Http;

if (!function_exists('report_header')) {
    function report_header($title)
    {
        return json_encode(view('layouts.report-header', ['title' => $title])->render());
    }
}



if (!function_exists('sms')) {
function sms($contacts, $message, $type = 'unicode')
{
    $api_key  = env('MRAM_API_KEY');
    $senderid = env('MRAM_SENDER_ID');

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
    $contacts = array_map(function ($number) {
        $number = preg_replace('/\D/', '', $number); // শুধু digits রাখবে
        return str_starts_with($number, '88') ? $number : '88' . $number;
    }, $contacts);

    // একসাথে join করা MRAM API অনুযায়ী
    $contacts = implode('+', $contacts);

    // -----------------------------
    // API call
    // -----------------------------
    try {
        $response = Http::asForm()->post("https://sms.mram.com.bd/smsapi", [
            "api_key"  => $api_key,
            "type"     => $type,
            "contacts" => $contacts,
            "senderid" => $senderid,
            "msg"      => $message,
        ]);

        $body = $response->body();

        // যদি API তে কোনো Error থাকে
        if (strpos($body, 'Error') !== false) {
            return false;
        }

        return true;

    } catch (\Exception $e) {
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

