<?php

function techno_bulk_sms($sender_id,$apiKey,$mobileNo,$message){
    $url = 'https://24smsbd.com/api/bulkSmsApi';
    $data = array('sender_id' => $sender_id,
     'apiKey' => $apiKey,
     'mobileNo' => $mobileNo,
     'message' =>$message
     );

     $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($curl);
        curl_close($curl);

       // echo $output;
    }

//REPORT HEADER
function report_header($title)
{
    return json_encode(view('layouts.report-header',['title'=>$title])->render()) ;
}

//SEND SMS
function sms($mobile_no=null,$message=null){

$ap_key=env('SMS_API');
$sender_id=env('SMS_SENDER_ID');

try {
    if($mobile_no != null && $message != null){
        techno_bulk_sms($sender_id,$ap_key,$mobile_no,$message);
      //  toast('SMS Sent to - '.$mobile_no,'success');
        return true;

     }else{
         return false;
     }
} catch (\Throwable $th) {
    //toast('SMS Sending Failed!','error');
    return false;
}


}

function techno_sms_current_balance($sender_id,$apiKey){
    $url = 'https://24smsbd.com/api/current-balance';
    $data = array('sender_id' => $sender_id,
    'apiKey' => $apiKey
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    $output = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($output);
    return $result->data->sms_limit;
    }

//CHECK SMS BALANCE
function sms_balance() {
    $ap_key=env('SMS_API');
    $sender_id=env('SMS_SENDER_ID');
try {
    return techno_sms_current_balance($sender_id,$ap_key);
} catch (\Throwable $th) {
    return 0;
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
