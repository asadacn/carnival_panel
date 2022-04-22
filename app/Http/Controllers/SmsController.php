<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\SMSLOG;
use Illuminate\Http\Request;

class SmsController extends Controller
{
   public function send_sms(Request $request)
   {

    $client = Client::findOrFail($request->client_id);

    // $response = Http::get('http://sms.asolution24.com/api/send?key=f044ca72dbd6b15c942f86c9245d836d22806212&priority=1&phone=88'.$client->contact.'&message='.$request->sms);
    $smslog = new SMSLOG();

    $smslog->client_id = $client->username;
    $smslog->contact = $client->contact;
    $smslog->sms = $request->sms;

    if (sms($client->contact, $request->sms)) {
        $smslog->status = true;
        $smslog->save();
       return true;
    }else{
        $smslog->status = false;
        $smslog->save();
        return false;

    } ;


   }
}
