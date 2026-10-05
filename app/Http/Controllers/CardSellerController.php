<?php

namespace App\Http\Controllers;

use App\Exports\CardSellerExport;
use App\Exports\CardSellerTemplate;
use App\Http\Requests\CreateCardSellerRequest;
use App\Http\Requests\UpdateCardSellerRequest;
use App\Imports\CardSellerImport;
use App\Models\CardSeller;
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
