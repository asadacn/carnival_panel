<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\SMS_TEMPALTE;
use App\Models\SMSLOG;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

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

  //  $request->client_status == "expiring" ?  $clients = Client::where('expiration',Carbon::tomorrow('Asia/Dhaka'))->get() : $clients = Client::where('status',$request->client_status)->get();
    //dd( $clients);


switch ($request->client_status) {
    case "expiring":
        $clients = Client::where('expiration',Carbon::tomorrow('Asia/Dhaka'))->get();
      break;
    case "registered":
        $clients = Client::where('status','registered')->get();
      break;
    case "expired":
        $clients = Client::where('status','expired')->get();
    case "expired_today":
        $clients = Client::where('expiration',Carbon::today('Asia/Dhaka'))->get();
      break;
    default:
    Flash::error("Select a clients group please!");
    return redirect()->back();
  }



    try {
        if(!empty($request->sms_body)){

            foreach($clients as $client){
                $smslog = new SMSLOG();
                $smslog->client_id = $client->username;
                $smslog->contact = $client->contact;
                $smslog->sms = $request->sms_body;

                    if (sms(  $client->contact, $request->sms_body )) {
                        $smslog->status = true;
                        $smslog->save();
                    }else{
                        $smslog->status = false;
                        $smslog->save();

                    } ;
                }

        }else{

        Flash::error("SMS Body Empty!");
        return redirect()->back();

        }

    } catch (\Throwable $th) {

        Flash::error("SMS Sending Failed!");
        return redirect()->back();

    }
    //end bulksms
    Flash::success("SMS Successfully Delivered!");
    return redirect()->back();

   }



   public function sms_log()
   {
       $SMSLOGS = SMSLOG::latest()->simplePaginate(25);

       return view('s_m_s_l_o_g_s.index',compact('SMSLOGS'));
   }

   public function create_bulk_sms(){

    $templates = SMS_TEMPALTE::all();

    $expiring_soon = Client::where('expiration',Carbon::tomorrow('Asia/Dhaka'))->count();
    $expired_today = Client::where('expiration',Carbon::today('Asia/Dhaka'))->count();

    return view('bulksms.create', compact(['templates','expiring_soon','expired_today']));
   }
}
