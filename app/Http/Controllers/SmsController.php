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
        // 1. Initial Validation
        if (!isset($request->client_status)) {
            Flash::error("Select a clients group please!");
            return redirect()->back();
        }

        $request->validate([
            'sms_body' => 'required|string|max:250',
            'client_status' => 'required|string',
        ]);

        $clients = collect();

        // 2. Build base query — optionally filter by ISP
        $ispCode = $request->isp_code;
        $baseQuery = Client::query();
        if (!empty($ispCode)) {
            $baseQuery->where('isp_code', strtolower($ispCode));
        }

        // 3. Get clients contacts based on the selected status
        if ($request->client_status === 'custom') {
            $request->validate(['custom_contacts' => 'required|string']);

            // FIX: Use new line (\r\n|\r|\n) as separator
            $custom_numbers = array_filter(
                array_map('trim', preg_split('/\r\n|\r|\n/', $request->custom_contacts))
            );

            if (empty($custom_numbers)) {
                Flash::error("No custom numbers were provided.");
                return redirect()->back();
            }
            $clients = collect($custom_numbers);

        } else {
            // Existing logic for database groups
            switch ($request->client_status) {
                case "expiring":
                    $clients = (clone $baseQuery)->where('expiration', Carbon::tomorrow('Asia/Dhaka'))->pluck('contact');
                    break;
                case "registered":
                    $clients = (clone $baseQuery)->where('status','registered')->pluck('contact');
                    break;
                case "expired":
                    $clients = (clone $baseQuery)->where('status','expired')->pluck('contact');
                    break;
                case "expired_today":
                    $clients = (clone $baseQuery)->where('expiration',Carbon::today('Asia/Dhaka'))->pluck('contact');
                    break;
                case "expired_this_month":
                    $clients = (clone $baseQuery)->where('status','expired')->whereYear('expiration', date('Y'))->whereMonth('expiration', date('m'))->pluck('contact');
                    break;

                default:
                    Flash::error("Invalid clients group selected!");
                    return redirect()->back();
            }
        }

        if ($clients->isEmpty()) {
            Flash::warning("No contacts found for the selected group or the custom list is empty.");
            return redirect()->back();
        }


        // 3. Send SMS
        try {
            if(!empty($request->sms_body)){
                sms( $clients, $request->sms_body, 'unicode' );

            }else{
                Flash::error("SMS Body Empty!");
                return redirect()->back();
            }

        } catch (\Throwable $th) {
            Flash::error("SMS Sending Failed!");
            return redirect()->back();
        }

        Flash::success("Bulk SMS successfully initiated to " . $clients->count() . " contacts" . (!empty($ispCode) ? " (ISP: " . ucfirst($ispCode) . ")" : "") . "!");
        return redirect()->back();
    }

    public function bulk_voice_campaign(Request $request)
    {
        if (!isset($request->client_status)) {
            Flash::error("Select a clients group please!");
            return redirect()->back();
        }

        // 1. Validate the request (title validation is done after cleaning)
        $request->validate([
            'broadcast_id' => 'required|integer',
            'campaign_title' => 'required|string|max:100',
            'sender' => 'required|string|max:20',
            'client_status' => 'required|string',
        ]);

        $clients = collect();

        // 2. Build base query — optionally filter by ISP
        $ispCode = $request->isp_code;
        $baseQuery = Client::query();
        if (!empty($ispCode)) {
            $baseQuery->where('isp_code', strtolower($ispCode));
        }

        // 3. Get clients contacts based on the selected status
        if ($request->client_status === 'custom') {
            $request->validate(['custom_contacts' => 'required|string']);

            // FIX: Use new line (\r\n|\r|\n) as separator
            $custom_numbers = array_filter(
                array_map('trim', preg_split('/\r\n|\r|\n/', $request->custom_contacts))
            );

            if (empty($custom_numbers)) {
                Flash::error("No custom numbers were provided.");
                return redirect()->back();
            }
            $clients = collect($custom_numbers);

        } else {
            // Existing logic for database groups
            switch ($request->client_status) {
                case "expiring":
                    $clients = (clone $baseQuery)->where('expiration', Carbon::tomorrow('Asia/Dhaka'))->pluck('contact');
                    break;
                case "registered":
                    $clients = (clone $baseQuery)->where('status', 'registered')->pluck('contact');
                    break;
                case "expired":
                    $clients = (clone $baseQuery)->where('status', 'expired')->pluck('contact');
                    break;
                case "expired_today":
                    $clients = (clone $baseQuery)->where('expiration', Carbon::today('Asia/Dhaka'))->pluck('contact');
                    break;
                case "expired_this_month":
                    $clients = (clone $baseQuery)->where('status', 'expired')->whereYear('expiration', date('Y'))->whereMonth('expiration', date('m'))->pluck('contact');
                    break;
                default:
                    Flash::error("Invalid clients group selected!");
                    return redirect()->back();
            }
        }

        if ($clients->isEmpty()) {
            Flash::warning("No clients found for the selected group. Voice campaign not sent.");
            return redirect()->back();
        }

        // 3. FIX: Normalize numbers to match the ^8801[0-9]{9}$ pattern for Elit Call API
        $numbers = $clients->map(function ($number) {
            $number = preg_replace('/\D/', '', $number);
            if (str_starts_with($number, '88')) {
                return $number;
            }
            // Prepend 88. This will result in 8801xxxxxxx format if input was 01xxxxxxx
            return '88' . $number;
        })->toArray();

        // Final filter to ensure API compliance (8801xxxxxxxx format)
        $numbers = array_filter($numbers, function($n) {
            return preg_match('/^8801[0-9]{9}$/', $n);
        });

        if (empty($numbers)) {
            Flash::error("All selected contacts failed number format validation after prepending '88'.");
            return redirect()->back();
        }


        // 4. FIX: Clean and ensure UNIQUE Campaign Title
        $campaignTitle = $request->campaign_title;

        // Clean Title for API compliance (keep only a-z, 0-9, space, and Bengali characters)
        $cleanTitle = preg_replace('/[^a-zA-Z0-9\s\p{Bengali}]/u', '', $campaignTitle);
        $cleanTitle = trim(preg_replace('/\s+/', ' ', $cleanTitle));

        // Ensure UNIQUNESS by appending a timestamp. Use a short format for base title.
        $baseTitle = substr($cleanTitle, 0, 80);
        $uniqueTitle = $baseTitle . ' ' . now()->format('YmdHis');

        if (empty(trim($baseTitle))) {
             $uniqueTitle = 'Voice Campaign ' . now()->format('YmdHis'); // Safe unique fallback
        }

        // The API title length is max 100
        $finalTitle = substr($uniqueTitle, 0, 100);

        // 5. Send the voice campaign
        try {
            $sender = preg_replace('/\D/', '', $request->sender);
            $result = sendVoiceCampaign(
                $finalTitle, // Use the final unique, cleaned title
                (int)$request->broadcast_id,
                $sender,
                $numbers // Use the normalized numbers
            );

            if ($result['success']) {
                $count = count($numbers);
                $ispLabel = !empty($ispCode) ? " (ISP: " . ucfirst($ispCode) . ")" : "";
                $campIdInfo = is_array($result['data']['campaign_id']) 
                    ? implode(', ', $result['data']['campaign_id']) 
                    : $result['data']['campaign_id'];

                $primaryCampaignId = is_array($result['data']['campaign_id']) 
                    ? $result['data']['campaign_id'][0] 
                    : $result['data']['campaign_id'];

                session()->flash('voice_campaign_id', $primaryCampaignId);

                Flash::success("Voice Campaign '{$finalTitle}' (ID: {$campIdInfo}) successfully started for {$count} clients{$ispLabel}!");
            } else {
                $errorMessage = isset($result['error']['detail']) ? $result['error']['detail'] : json_encode($result['error']);
                Flash::error("Voice Campaign sending failed! Error: " . $errorMessage);
            }
        } catch (\Throwable $th) {
            Flash::error("An unexpected error occurred during Voice Campaign submission.\n" . $th->getMessage());
        }

        return redirect()->back();
    }

    /**
     * Get real-time status of a voice campaign using Elit Call API
     */
    public function get_voice_campaign_status($campaignId)
    {
        $result = getVoiceCampaignDetails($campaignId);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'data'    => $result['data'],
            ]);
        }

        return response()->json([
            'success' => false,
            'error'   => $result['error'] ?? 'Failed to retrieve campaign details.',
        ], 400);
    }

    /**
     * DRY RUN: Return how many contacts would receive the message
     * based on the selected client_status + optional isp_code filter.
     * No message is ever sent.
     */
    public function preview_bulk_contacts(Request $request)
    {
        $status  = $request->input('client_status', '');
        $ispCode = $request->input('isp_code', '');

        if (empty($status)) {
            return response()->json(['count' => 0, 'label' => '—', 'error' => 'No group selected.']);
        }

        // Custom numbers: just count the lines
        if ($status === 'custom') {
            $raw = $request->input('custom_contacts', '');
            $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $raw)));
            return response()->json([
                'count' => count($lines),
                'label' => 'Custom Numbers',
                'isp'   => $ispCode ?: 'All ISPs',
            ]);
        }

        // Build base query with optional ISP filter
        $query = Client::query();
        if (!empty($ispCode)) {
            $query->where('isp_code', strtolower($ispCode));
        }

        $count = match ($status) {
            'expiring'          => (clone $query)->where('expiration', Carbon::tomorrow('Asia/Dhaka'))->count(),
            'registered'        => (clone $query)->where('status', 'registered')->count(),
            'expired'           => (clone $query)->where('status', 'expired')->count(),
            'expired_today'     => (clone $query)->where('expiration', Carbon::today('Asia/Dhaka'))->count(),
            'expired_this_month'=> (clone $query)->where('status', 'expired')
                                        ->whereYear('expiration', date('Y'))
                                        ->whereMonth('expiration', date('m'))
                                        ->count(),
            default             => null,
        };

        if (is_null($count)) {
            return response()->json(['count' => 0, 'error' => 'Invalid group.']);
        }

        $groupLabels = [
            'expiring'           => 'Expiring Tomorrow',
            'registered'         => 'Registered',
            'expired'            => 'Expired',
            'expired_today'      => 'Expired Today',
            'expired_this_month' => 'Expired This Month',
        ];

        return response()->json([
            'count' => $count,
            'label' => $groupLabels[$status] ?? $status,
            'isp'   => !empty($ispCode) ? ucfirst($ispCode) : 'All ISPs',
        ]);
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
        $expired_this_month = Client::where('status','expired')->whereYear('expiration', date('Y'))->whereMonth('expiration', date('m'))->count();

        return view('bulksms.create', compact(['templates','expiring_soon','expired_today','expired_this_month']));
    }
}
