<?php

namespace App\Http\Controllers;

use App\Exports\CardSellerExport;
use App\Exports\CardSellerTemplate;
use App\Http\Requests\CreateCardSellerRequest;
use App\Http\Requests\UpdateCardSellerRequest;
use App\Imports\CardSellerImport;
use App\Models\CardSeller;
use App\Models\SMS_TEMPALTE;
use App\Models\SMSLOG;
use App\Models\SmsCampaign;
use App\Repositories\CardSellerRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Yajra\DataTables\DataTables;

class CardSellerController extends AppBaseController
{
    /** @var  CardSellerRepository */
    private $cardSellerRepository;

    public function __construct(CardSellerRepository $cardSellerRepo)
    {
        $this->cardSellerRepository = $cardSellerRepo;
    }

    /**
     * Display a listing of the CardSeller.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = CardSeller::query()->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('contact', function ($cardSeller) {
                    if (empty($cardSeller->contact)) {
                        return '<span class="text-muted">-</span>';
                    }
                    return '<a href="tel:' . e($cardSeller->contact) . '" class="text-decoration-none fw-semibold">' . e($cardSeller->contact) . '</a>';
                })
                ->editColumn('store_title', fn ($cardSeller) => $cardSeller->store_title ?: '<span class="text-muted">-</span>')
                ->editColumn('address', function ($cardSeller) {
                    if (empty($cardSeller->address)) {
                        return '<span class="text-muted">-</span>';
                    }
                    return '<span title="' . e($cardSeller->address) . '">' . e(mb_strimwidth($cardSeller->address, 0, 60, '…')) . '</span>';
                })
                ->editColumn('created_at', function ($cardSeller) {
                    return \Carbon\Carbon::parse($cardSeller->created_at)
                        ->setTimezone('Asia/Dhaka')
                        ->format('d-M-Y');
                })
                ->addColumn('action', function ($cardSeller) {
                    $viewUrl = route('cardSellers.show', $cardSeller->id);
                    $editUrl = route('cardSellers.edit', $cardSeller->id);
                    $name    = addslashes($cardSeller->name);
                    $contact = addslashes($cardSeller->contact);

                    return <<<EOT
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{$viewUrl}" class="btn btn-sm btn-light border" title="View"><i class="fas fa-eye text-primary"></i></a>
                        <a href="{$editUrl}" class="btn btn-sm btn-light border" title="Edit"><i class="fas fa-edit text-warning"></i></a>
                        <button type="button" class="btn btn-sm btn-light border" title="Send SMS" onclick="sendCardSellerSms({$cardSeller->id}, '{$name}')"><i class="fas fa-paper-plane text-indigo"></i></button>
                        <button type="button" class="btn btn-sm btn-light border" title="Delete" onclick="deleteCardSeller({$cardSeller->id}, '{$name}')"><i class="fas fa-trash text-danger"></i></button>
                        <button type="button" class="btn btn-sm btn-light border" title="Copy contact" onclick="copyCardSellerContact('{$contact}')"><i class="fas fa-copy text-info"></i></button>
                    </div>
                    EOT;
                })
                ->rawColumns(['contact', 'store_title', 'address', 'action'])
                ->make(true);
        }

        $stats = [
            'total'       => CardSeller::count(),
            'withContact' => CardSeller::whereNotNull('contact')->where('contact', '!=', '')->count(),
            'withStore'   => CardSeller::whereNotNull('store_title')->where('store_title', '!=', '')->count(),
            'newThisMonth' => CardSeller::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        return view('card_sellers.index', compact('stats'));
    }

    /**
     * Show the form for creating a new CardSeller.
     *
     * @return Response
     */
    public function create()
    {
        return view('card_sellers.create');
    }

    /**
     * Store a newly created CardSeller in storage.
     *
     * @param CreateCardSellerRequest $request
     *
     * @return Response
     */
    public function store(CreateCardSellerRequest $request)
    {
        $input = $request->all();

        $cardSeller = $this->cardSellerRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/cardSellers.singular')]));

        return redirect(route('cardSellers.index'));
    }

    /**
     * Display the specified CardSeller.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cardSeller = $this->cardSellerRepository->find($id);

        if (empty($cardSeller)) {
            Flash::error(__('messages.not_found', ['model' => __('models/cardSellers.singular')]));

            return redirect(route('cardSellers.index'));
        }

        return view('card_sellers.show')->with('cardSeller', $cardSeller);
    }

    /**
     * Show the form for editing the specified CardSeller.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cardSeller = $this->cardSellerRepository->find($id);

        if (empty($cardSeller)) {
            Flash::error(__('messages.not_found', ['model' => __('models/cardSellers.singular')]));

            return redirect(route('cardSellers.index'));
        }

        return view('card_sellers.edit')->with('cardSeller', $cardSeller);
    }

    /**
     * Update the specified CardSeller in storage.
     *
     * @param int $id
     * @param UpdateCardSellerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCardSellerRequest $request)
    {
        $cardSeller = $this->cardSellerRepository->find($id);

        if (empty($cardSeller)) {
            Flash::error(__('messages.not_found', ['model' => __('models/cardSellers.singular')]));

            return redirect(route('cardSellers.index'));
        }

        $cardSeller = $this->cardSellerRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/cardSellers.singular')]));

        return redirect(route('cardSellers.index'));
    }

    /**
     * Show the form for importing CardSellers from Excel/CSV.
     *
     * @return Response
     */
    public function create_import()
    {
        return view('card_sellers.import');
    }

    /**
     * Export all CardSellers to Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new CardSellerExport, 'card_sellers.xlsx');
    }

    /**
     * Show the compose form for SMS announcements/offers to card sellers.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create_sms(Request $request)
    {
        $selectedInput = $request->input('selected', []);
        if (is_string($selectedInput)) {
            $selectedInput = explode(',', $selectedInput);
        }
        $selectedIds = collect($selectedInput)
            ->filter()
            ->map(function ($id) {
                return (int) $id;
            })
            ->values()
            ->all();

        $totalSellers = CardSeller::count();
        $withContact  = CardSeller::whereNotNull('contact')->where('contact', '!=', '')->count();

        $selected = collect();
        if (!empty($selectedIds)) {
            $selected = CardSeller::whereIn('id', $selectedIds)
                ->whereNotNull('contact')
                ->where('contact', '!=', '')
                ->get(['id', 'name', 'contact']);
        }

        $templates = SMS_TEMPALTE::orderBy('title')->get(['id', 'title', 'sms_template']);

        return view('card_sellers.sms', compact('totalSellers', 'withContact', 'selected', 'templates'));
    }

    /**
     * Send an SMS announcement/offer to all or selected card sellers.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function send_sms(Request $request)
    {
        $request->validate([
            'message'     => 'required|string|max:1000',
            'mode'        => 'required|in:all,selected',
            'selected_ids' => 'required_if:mode,selected|array|min:1',
            'selected_ids.*' => 'integer|exists:card_sellers,id',
        ]);

        $message = trim($request->message);

        if ($request->mode === 'selected') {
            $sellers = CardSeller::whereIn('id', (array) $request->selected_ids)
                ->whereNotNull('contact')
                ->where('contact', '!=', '')
                ->get(['id', 'name', 'contact']);
        } else {
            $sellers = CardSeller::whereNotNull('contact')
                ->where('contact', '!=', '')
                ->orderBy('id')
                ->get(['id', 'name', 'contact']);
        }

        if ($sellers->isEmpty()) {
            Flash::warning(__('No card sellers with a contact number found.'));

            return back()->withInput();
        }

        $charCount = mb_strlen($message, 'UTF-8');
        $smsParts  = $charCount <= 70 ? 1 : (int) ceil($charCount / 67);

        $campaign = SmsCampaign::create([
            'user_id'          => auth()->id(),
            'title'            => 'Card Seller SMS (' . $sellers->count() . ')',
            'target_group'     => 'card_sellers',
            'message_template' => $message,
            'total_recipients' => $sellers->count(),
            'status'           => 'processing',
        ]);

        $successCount = 0;
        $failedCount  = 0;
        $now          = now();

        $sellers->chunk(100)->each(function ($chunk) use ($campaign, $message, $charCount, $smsParts, $now, &$successCount, &$failedCount) {
            $contacts = $chunk->pluck('contact')->toArray();

            $isSent = false;
            try {
                $isSent = sms($contacts, $message, 'unicode');
            } catch (\Throwable $th) {
                $isSent = false;
            }

            $logRows = [];
            foreach ($chunk as $seller) {
                $logRows[] = [
                    'client_identifier' => 'card_seller:' . $seller->id,
                    'campaign_id'       => $campaign->id,
                    'user_id'           => auth()->id(),
                    'contact'           => $seller->contact,
                    'sms'               => $message,
                    'character_count'   => $charCount,
                    'sms_count'         => $smsParts,
                    'message_type'      => 'card_seller',
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

        Flash::success(
            __('SMS processed: :success sent', ['success' => $successCount])
            . ($failedCount > 0 ? __(', :failed failed', ['failed' => $failedCount]) : '')
            . '.'
        );

        return redirect()->route('cardSellers.index');
    }

    /**
     * Send a single SMS to one card seller (AJAX).
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_single_sms(Request $request)
    {
        $request->validate([
            'card_seller_id' => 'required|integer|exists:card_sellers,id',
            'sms'            => 'required|string|max:1000',
        ]);

        $cardSeller = CardSeller::findOrFail($request->card_seller_id);

        if (empty($cardSeller->contact)) {
            return response()->json([
                'success' => false,
                'message' => __('This card seller has no contact number.'),
            ]);
        }

        $smsText    = trim($request->sms);
        $charCount  = mb_strlen($smsText, 'UTF-8');
        $smsParts   = $charCount <= 70 ? 1 : (int) ceil($charCount / 67);

        $isSent = false;
        try {
            $isSent = sms($cardSeller->contact, $smsText, 'unicode');
        } catch (\Throwable $th) {
            $isSent = false;
        }

        $smslog = new SMSLOG();
        $smslog->client_identifier = 'card_seller:' . $cardSeller->id;
        $smslog->user_id           = auth()->id();
        $smslog->contact           = $cardSeller->contact;
        $smslog->sms               = $smsText;
        $smslog->character_count   = $charCount;
        $smslog->sms_count         = $smsParts;
        $smslog->message_type      = 'card_seller';
        $smslog->encoding          = 'unicode';
        $smslog->gateway           = 'mram';
        $smslog->status            = $isSent ? '1' : '0';
        $smslog->error_message     = $isSent ? null : 'Failed to deliver to gateway';
        $smslog->sent_at           = $isSent ? now() : null;
        $smslog->save();

        if ($isSent) {
            return response()->json(['success' => true, 'message' => __('SMS sent successfully.')]);
        }

        return response()->json([
            'success' => false,
            'message' => __('SMS sending failed. Please check the API or contact number.'),
        ]);
    }

    /**
     * Download a sample import template.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function template()
    {
        return Excel::download(new CardSellerTemplate, 'card_seller_template.xlsx');
    }

    /**
     * Import CardSellers from an Excel/CSV file.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'cardseller_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $importer = new CardSellerImport();

            Excel::import($importer, $request->file('cardseller_file'));

            $summary = sprintf(
                'Import complete — %d new, %d updated',
                $importer->inserted,
                $importer->updated
            );

            if (!empty($importer->skipped)) {
                $summary .= sprintf(', %d skipped', count($importer->skipped));
            }

            if (!empty($importer->errors)) {
                $summary .= sprintf(', %d failed', count($importer->errors));
            }

            Flash::success($summary);

            return back()->with([
                'import_skipped' => $importer->skipped,
                'import_errors'  => $importer->errors,
            ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = collect($e->failures())->map(fn ($f) =>
                "Row {$f->row()}: " . implode(', ', $f->errors())
            )->toArray();

            Flash::error(__('Import Failed') . ' — ' . implode(' | ', $failures));

            return back();

        } catch (\Exception $e) {
            Flash::error(__('Import Failed') . ': ' . $e->getMessage());

            return back();
        }
    }

    /**
     * Remove the specified CardSeller from storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $cardSeller = $this->cardSellerRepository->find($id);

        if (empty($cardSeller)) {
            Flash::error(__('messages.not_found', ['model' => __('models/cardSellers.singular')]));

            return redirect(route('cardSellers.index'));
        }

        $this->cardSellerRepository->delete($id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => __('messages.deleted', ['model' => __('models/cardSellers.singular')]),
            ]);
        }

        Flash::success(__('messages.deleted', ['model' => __('models/cardSellers.singular')]));

        return redirect(route('cardSellers.index'));
    }
}
