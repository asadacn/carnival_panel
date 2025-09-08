<?php

    // function techno_bulk_sms($ap_key,$sender_id,$mobile_no,$message,$user_email){
    //     $url = 'https://24bulksms.com/24bulksms/api/api-sms-send';
    //     $data = array('api_key' => $ap_key,
    //      'sender_id' => $sender_id,
    //      'message' => $message,
    //      'mobile_no' =>$mobile_no,
    //      'user_email'=> $user_email
    //      );

    //     // use key 'http' even if you send the request to https://...
    //      $curl = curl_init($url);
    //     curl_setopt($curl, CURLOPT_POST, true);
    //     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //     $output = curl_exec($curl);
    //     curl_close($curl);
    // }

//REPORT HEADER

use Illuminate\Support\Facades\Http;

function report_header($title)
{
    return json_encode(view('layouts.report-header',['title'=>$title])->render()) ;
}

//SEND SMS
// function sms($mobile_no=null,$message=null){

//     $ap_key=env('SMS_API','175392409647823620230102091243pmF5lrciDk');
//     $sender_id=env('SMS_SENDER_ID',297);
//     $email = env('SMS_SENDER_EMAIL','asadacn@gmail.com');

// try {
//     if($mobile_no != null && $message != null){
//         techno_bulk_sms($ap_key,$sender_id,$mobile_no,$message,$email);
//       //  toast('SMS Sent to - '.$mobile_no,'success');
//         return true;

//      }else{
//          return false;
//      }
// } catch (\Throwable $th) {
//     //toast('SMS Sending Failed!','error');
//     return false;
// }


// }

// function sms($contacts, $message, $type = 'unicode')
//     {
//         $api_key  = env('MRAM_API_KEY');
//         $senderid = env('MRAM_SENDER_ID');

//         // যদি $contacts array হয়, তাহলে + দিয়ে join করুন
//         if (is_array($contacts)) {
//             $contacts = implode('+', $contacts);
//         }
// dd($contacts);
//         try {
//             $response = Http::asForm()->post("https://sms.mram.com.bd/smsapi", [
//                 "api_key"  => $api_key,
//                 "type"     => $type,
//                 "contacts" => $contacts,
//                 "senderid" => $senderid,
//                 "msg"      => $message,
//             ]);

//             $body = $response->body();
//             // API Success check
//             if (strpos($body, 'Error') !== false) {
//                 return false;
//             }
//             return true;

//         } catch (\Exception $e) {
//             return false;
//         }
//     }



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
}



//CHECK SMS BALANCE
// function sms_balance() {

// $url = 'https://24bulksms.com/24bulksms/api/user-info-chack';
// $ap_key='175392409647823620230102091243pmF5lrciDk';
// $email='asadacn@gmail.com';
// $data = array('api_key' => $ap_key,
//  'user_email'=>$email
//  );



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
}



//ENG TO BAN DIGIT CONVERTER
function en2bnNumber($number){
    $replace_array= array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
    $search_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
    $en_number = str_replace($search_array, $replace_array,  $number);

    return $en_number;
}


//BDT MONEY FORMATTER
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


