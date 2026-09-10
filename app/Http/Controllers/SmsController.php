<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\SMS_TEMPALTE;
use App\Models\SMSLOG;
use App\Models\SmsCampaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class SmsController extends Controller
{
    public function send_sms(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'sms'       => 'required|string|max:1000',
        ]);

        $client = Client::findOrFail($request->client_id);
        $smsText = trim($request->sms);
        $charCount = mb_strlen($smsText, 'UTF-8');
        $smsParts = $charCount <= 70 ? 1 : (int) ceil($charCount / 67);

        $isSent = sms($client->contact, $smsText, 'unicode');

        $smslog = new SMSLOG();
        $smslog->client_id         = $client->id;
        $smslog->client_identifier = $client->username;
        $smslog->user_id           = auth()->id();
        $smslog->contact           = $client->contact;
        $smslog->sms               = $smsText;
        $smslog->character_count   = $charCount;
        $smslog->sms_count         = $smsParts;
        $smslog->message_type      = 'single';
        $smslog->encoding          = 'unicode';
        $smslog->gateway           = 'mram';
        $smslog->status            = $isSent ? '1' : '0';
        $smslog->error_message     = $isSent ? null : 'Failed to deliver to gateway';
        $smslog->sent_at           = $isSent ? now() : null;
        $smslog->save();

        if ($isSent) {
            return response()->json(['success' => true, 'message' => 'SMS sent successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'SMS sending failed. Please check the API or contact number.'], 200);
        }
    }

    public function bulk_sms(Request $request)
    {
        // Scenario 1: Selected clients array from UI/Datatables
        if ($request->has('clients')) {
            $request->validate([
                'sms'       => 'required|string|max:1000',
                'clients'   => 'required|array|min:1',
                'clients.*' => 'integer|distinct|exists:clients,id',
            ]);

            $targetClients = Client::whereIn('id', $request->input('clients'))
                ->whereNotNull('contact')
                ->where('contact', '!=', '')
                ->get(['id', 'username', 'contact']);

            if ($targetClients->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid contact numbers found for the selected clients.',
                ], 422);
            }

            $smsText = trim($request->input('sms'));
            $charCount = mb_strlen($smsText, 'UTF-8');
            $smsParts = $charCount <= 70 ? 1 : (int) ceil($charCount / 67);

            // Create Master Campaign Record
            $campaign = SmsCampaign::create([
                'user_id'          => auth()->id(),
                'title'            => 'Selected Clients SMS (' . $targetClients->count() . ')',
                'target_group'     => 'selected',
                'message_template' => $smsText,
                'total_recipients' => $targetClients->count(),
                'status'           => 'processing',
            ]);

            $successCount = 0;
            $failedCount  = 0;
            $now = now();

            $targetClients->chunk(100)->each(function ($chunk) use ($campaign, $smsText, $charCount, $smsParts, $now, &$successCount, &$failedCount) {
                $contacts = $chunk->pluck('contact')->toArray();
                $isSent = false;
                try {
                    $isSent = sms($contacts, $smsText, 'unicode');
                } catch (\Throwable $th) {
                    $isSent = false;
                }

                $logRows = [];
                foreach ($chunk as $cl) {
                    $logRows[] = [
                        'client_id'         => $cl->id,
                        'client_identifier' => $cl->username,
                        'campaign_id'       => $campaign->id,
                        'user_id'           => auth()->id(),
                        'contact'           => $cl->contact,
                        'sms'               => $smsText,
                        'character_count'   => $charCount,
                        'sms_count'         => $smsParts,
                        'message_type'      => 'bulk',
                        'encoding'          => 'unicode',
                        'gateway'           => 'mram',
                        'status'            => $isSent ? '1' : '0',
                        'error_message'     => $isSent ? null : 'Gateway dispatch failed',
                        'sent_at'           => $isSent ? $now : null,
                        'created_at'        => $now,
                        'updated_at'        => $now,
                    ];
                }
                SMSLOG::insert($logRows);

                if ($isSent) {
                    $successCount += count($chunk);
                } else {
                    $failedCount += count($chunk);
                }
            });

            $campaign->update([
                'successful_count' => $successCount,
                'failed_count'     => $failedCount,
                'status'           => $failedCount === 0 ? 'completed' : ($successCount > 0 ? 'partially_failed' : 'failed'),
                'sent_at'          => now(),
            ]);

            return response()->json([
                'success' => $successCount > 0,
                'message' => 'SMS sent successfully to ' . $successCount . ' client(s).' . ($failedCount > 0 ? " ({$failedCount} failed)" : ''),
            ]);
        }

        // Scenario 2: Target group selection from Bulk SMS create view
        if (!isset($request->client_status)) {
            Flash::error("Select a clients group please!");
            return redirect()->back();
        }

        $request->validate([
            'sms_body'      => 'required|string|max:1000',
            'client_status' => 'required|string',
        ]);

        $smsText = trim($request->sms_body);
        $charCount = mb_strlen($smsText, 'UTF-8');
        $smsParts = $charCount <= 70 ? 1 : (int) ceil($charCount / 67);

        $ispCode = $request->isp_code;
        $baseQuery = Client::query()->whereNotNull('contact')->where('contact', '!=', '');
        if (!empty($ispCode)) {
            $baseQuery->where('isp_code', strtolower($ispCode));
        }

        $clientsCollection = collect();

        if ($request->client_status === 'custom') {
            $request->validate(['custom_contacts' => 'required|string']);

            $custom_numbers = array_filter(
                array_map('trim', preg_split('/\r\n|\r|\n/', $request->custom_contacts))
            );

            if (empty($custom_numbers)) {
                Flash::error("No custom numbers were provided.");
                return redirect()->back();
            }

            // Map custom numbers to objects with null client_id
            $clientsCollection = collect($custom_numbers)->map(function ($phone) {
                // If phone exists in clients, attempt to resolve client_id
                $found = Client::where('contact', $phone)->first(['id', 'username']);
                return (object)[
                    'id'       => $found ? $found->id : null,
                    'username' => $found ? $found->username : null,
                    'contact'  => $phone,
                ];
            });
        } else {
            switch ($request->client_status) {
                case "expiring":
                    $clientsCollection = (clone $baseQuery)->where('expiration', Carbon::tomorrow('Asia/Dhaka'))->get(['id', 'username', 'contact']);
                    break;
                case "registered":
                    $clientsCollection = (clone $baseQuery)->where('status', 'registered')->get(['id', 'username', 'contact']);
                    break;
                case "expired":
                    $clientsCollection = (clone $baseQuery)->where('status', 'expired')->get(['id', 'username', 'contact']);
                    break;
                case "expired_today":
                    $clientsCollection = (clone $baseQuery)->where('expiration', Carbon::today('Asia/Dhaka'))->get(['id', 'username', 'contact']);
                    break;
                case "expired_this_month":
                    $clientsCollection = (clone $baseQuery)->where('status', 'expired')->whereYear('expiration', date('Y'))->whereMonth('expiration', date('m'))->get(['id', 'username', 'contact']);
                    break;
                default:
                    Flash::error("Invalid clients group selected!");
                    return redirect()->back();
            }
        }

        if ($clientsCollection->isEmpty()) {
            Flash::warning("No contacts found for the selected group or the custom list is empty.");
            return redirect()->back();
        }

        // Create campaign header
        $campaign = SmsCampaign::create([
            'user_id'          => auth()->id(),
            'title'            => ucfirst(str_replace('_', ' ', $request->client_status)) . ' (' . $clientsCollection->count() . ')' . (!empty($ispCode) ? ' - ' . strtoupper($ispCode) : ''),
            'target_group'     => $request->client_status,
            'isp_code'         => $ispCode,
            'message_template' => $smsText,
            'total_recipients' => $clientsCollection->count(),
            'status'           => 'processing',
        ]);

        $successCount = 0;
        $failedCount  = 0;
        $now = now();

        $clientsCollection->chunk(100)->each(function ($chunk) use ($campaign, $smsText, $charCount, $smsParts, $now, &$successCount, &$failedCount) {
            $contacts = $chunk->pluck('contact')->filter()->values()->toArray();
            $isSent = false;
            if (!empty($contacts)) {
                try {
                    $isSent = sms($contacts, $smsText, 'unicode');
                } catch (\Throwable $th) {
                    $isSent = false;
                }
            }

            $logRows = [];
            foreach ($chunk as $cl) {
                $logRows[] = [
                    'client_id'         => $cl->id,
                    'client_identifier' => $cl->username,
                    'campaign_id'       => $campaign->id,
                    'user_id'           => auth()->id(),
                    'contact'           => $cl->contact,
                    'sms'               => $smsText,
                    'character_count'   => $charCount,
                    'sms_count'         => $smsParts,
                    'message_type'      => 'bulk',
                    'encoding'          => 'unicode',
                    'gateway'           => 'mram',
                    'status'            => $isSent ? '1' : '0',
                    'error_message'     => $isSent ? null : 'Bulk gateway dispatch failed',
                    'sent_at'           => $isSent ? $now : null,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];
            }
            SMSLOG::insert($logRows);

            if ($isSent) {
                $successCount += count($chunk);
            } else {
                $failedCount += count($chunk);
            }
        });

        $campaign->update([
            'successful_count' => $successCount,
            'failed_count'     => $failedCount,
            'status'           => $failedCount === 0 ? 'completed' : ($successCount > 0 ? 'partially_failed' : 'failed'),
            'sent_at'          => now(),
        ]);

        Flash::success("Bulk SMS successfully processed: {$successCount} sent" . ($failedCount > 0 ? ", {$failedCount} failed." : "!"));
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
