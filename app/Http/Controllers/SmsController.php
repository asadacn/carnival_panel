<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\SMS_TEMPALTE;
use App\Models\SMSLOG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

   public function bulk_sms(Request $request)
   {

    //$client = Client::findOrFail($request->client_id);

    // $response = Http::get('http://sms.asolution24.com/api/send?key=f044ca72dbd6b15c942f86c9245d836d22806212&priority=1&phone=88'.$client->contact.'&message='.$request->sms);

    $i=0;
    try {
        foreach($request->clients as $client){
            $smslog = new SMSLOG();
            $smslog->client_id = $request->clients[$i]['username'];
            $smslog->contact = $request->clients[$i]['contact'];
            $smslog->sms = $request->sms;

                if (sms( $request->clients[$i]['contact'], $request->sms)) {
                    $smslog->status = true;
                    $smslog->save();
                }else{
                    $smslog->status = false;
                    $smslog->save();

                } ;

        $i++;
            }
    } catch (\Throwable $th) {
        return false;

    }
    //end bulksms

    return true;

   }

   public function reg_bulk_sms(Request $request)
   {

    $registered_clients = DB::table('clients')
    ->where('status','Registered')->get();


    try {
        foreach($registered_clients as $client){
            $smslog = new SMSLOG();
            $smslog->client_id = $client->username;
            $smslog->contact = $client->contact;
            $smslog->sms = $request->sms;

                if (sms( $client->contact, $request->sms)) {
                    $smslog->status = true;
                    $smslog->save();
                }else{
                    $smslog->status = false;
                    $smslog->save();

                } ;


            }
    } catch (\Throwable $th) {
        return false;

    }
    //end bulksms

    return true;

   }


   public function sms_log()
   {
       $SMSLOGS = SMSLOG::latest()->simplePaginate(25);

       return view('s_m_s_l_o_g_s.index',compact('SMSLOGS'));
   }

   public function create_bulk_sms(){

    $templates = SMS_TEMPALTE::all();

    return view('bulksms.create', compact('templates'));
   }
}
