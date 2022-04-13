<?php

function getColor($url) #GET RANDOM COLOR FORM IMAGE
{
    try {
         $img = Intervention\Image\ImageManagerStatic::make($url);

         return $img->pickColor(rand(0,100), rand(0,100), 'hex');

    } catch (\Throwable $th) {

       return "";

    }

}

function production($size,$piece,$unit){ //production calculation

    $size=filter_var($size, FILTER_SANITIZE_NUMBER_INT);
    $piece= filter_var($piece, FILTER_SANITIZE_NUMBER_INT);

    $total= (int) $size*(int) $piece * $unit;

    // if ($total>=1000) {
    //    return $total/1000;
    // }else return $total;
    return $total/1000;
   }

   // GET CUSTOMER DUE REPORT
   function getDue($id=0)
   {
       if (\App\Customer::find($id)) {



       $total_due =\App\Customer::find($id)->order()->sum('due_amount');
       $paid = \App\Customer::find($id)->payment()->sum('paid_amount');
       $bulk_due = \App\BulkSell::where('customer_id',$id)->sum('due_amount');

       $current_due = ($total_due+$bulk_due) - $paid ;

       $dues = [
           "total_due"  =>$total_due+$bulk_due,
           "current_due"=>$current_due,
           "paid_amount"=>$paid,
           "bulk_due"   =>$bulk_due
           ];

           return $dues;

       }else{

        $dues = [
            "total_due"  =>0,
            "current_due"=>0,
            "paid_amount"=>0,
            "bulk_due"   =>0
            ];

            return $dues;

       }


   }


// function techno_bulk_sms($ap_key,$sender_id,$mobile_no,$message,$user_email){
// 	$url = 'https://71bulksms.com/sms_api/bulk_sms_sender_2.php';
// 	$data = array('api_key' => $ap_key,
// 	 'sender_id' => $sender_id,
// 	 'message' => $message,
// 	 'mobile_no' =>$mobile_no,
// 	 'user_email'=> $user_email
// 	 );

// 	// use key 'http' even if you send the request to https://...
// 	$options = array(
// 		'http' => array(
// 			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
// 			'method'  => 'POST',
// 			'content' => http_build_query($data)
// 		)
// 	);
// 	$context  = stream_context_create($options);
// 	$result = file_get_contents($url, false, $context);
//     //print_r($result);
//     return $result;
// }

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

$ap_key='QXNhZGE6QXNhc2RhNDQ4';
$sender_id='345';

try {
    if($mobile_no != null && $message != null){
        techno_bulk_sms($sender_id,$ap_key,$mobile_no,$message);
        toast('SMS Sent to - '.$mobile_no,'success');
        return true;

     }else{
         return false;
     }
} catch (\Throwable $th) {
    toast('SMS Sending Failed!','error');
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
$ap_key='QXNhZGE6QXNhc2RhNDQ4';
$sender_id='345';
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
