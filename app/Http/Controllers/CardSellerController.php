<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCardSellerRequest;
use App\Http\Requests\UpdateCardSellerRequest;
use App\Repositories\CardSellerRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\CardSeller;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\Imports\CardSellerImport;
use Maatwebsite\Excel\Facades\Excel;

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
        $cardSellers = $this->cardSellerRepository->all();

        return view('card_sellers.index')
            ->with('cardSellers', $cardSellers);
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
     * Remove the specified CardSeller from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cardSeller = $this->cardSellerRepository->find($id);

        if (empty($cardSeller)) {
            Flash::error(__('messages.not_found', ['model' => __('models/cardSellers.singular')]));

            return redirect(route('cardSellers.index'));
        }

        $this->cardSellerRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/cardSellers.singular')]));

        return redirect(route('cardSellers.index'));
    }

    public function export()
    {


        return Excel::download(new CardSellerExport, 'cardseller.xlsx');
    }

    public function import()
    {
        if (request()->file('cardseller_file')) {
            if (Excel::import(new CardSellerImport, request()->file('cardseller_file'))) {
                Flash::success(__('Import Successfull'));
                return back()->with('success', 'All good!');
            } else {
                Flash::error(__('Import Failed'));
                return back()->with('error', 'Import Failed');
            }
        } else {
            Flash::warning(__('Please select a file first !'));
            return back()->with('error', 'Please select a file first !');
        }
    }


    public function erase()
    {
        Client::query()->truncate();
        flash('Truncate Successfull')->success();
        //Flash::success(__('Truncate Successfull', ['model' => __('models/clients.singular')]));
        return back();
    }
}
