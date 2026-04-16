<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvestmentRequest;
use App\Http\Requests\UpdateInvestmentRequest;
use App\Repositories\InvestmentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Illuminate\Database\Eloquent\Model;
class InvestmentController extends AppBaseController
{
    /** @var  InvestmentRepository */
    private $investmentRepository;

    public function __construct(InvestmentRepository $investmentRepo)
    {
        $this->investmentRepository = $investmentRepo;
    }

    /**
     * Display a listing of the Investment.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $investments = $this->investmentRepository->all();

        // Dashboard Metrics
        $totalInvestment = \App\Models\Investment::sum('amount');
        
        $now = \Carbon\Carbon::now();
        $thisMonthInvestment = \App\Models\Investment::whereYear('created_at', $now->year)
                                    ->whereMonth('created_at', $now->month)
                                    ->sum('amount');
                                    
        $thisYearInvestment = \App\Models\Investment::whereYear('created_at', $now->year)
                                    ->sum('amount');

        $investmentsByType = \App\Models\Investment::select('type', \DB::raw('SUM(amount) as total'))
                                ->groupBy('type')
                                ->orderBy('total', 'desc')
                                ->get();

        $investmentsByInvestor = \App\Models\Investment::select('invested_by', \DB::raw('SUM(amount) as total'))
                                ->groupBy('invested_by')
                                ->orderBy('total', 'desc')
                                ->get();

        return view('investments.index')
            ->with('investments', $investments)
            ->with('totalInvestment', $totalInvestment)
            ->with('thisMonthInvestment', $thisMonthInvestment)
            ->with('thisYearInvestment', $thisYearInvestment)
            ->with('investmentsByType', $investmentsByType)
            ->with('investmentsByInvestor', $investmentsByInvestor);
    }

    /**
     * Show the form for creating a new Investment.
     *
     * @return Response
     */
    public function create()
    {
        return view('investments.create');
    }

    /**
     * Store a newly created Investment in storage.
     *
     * @param CreateInvestmentRequest $request
     *
     * @return Response
     */
    public function store(CreateInvestmentRequest $request)
    {
        $input = $request->all();

        $investment = $this->investmentRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/investments.singular')]));

        return redirect(route('investments.index'));
    }

    /**
     * Display the specified Investment.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $investment = $this->investmentRepository->find($id);

        if (empty($investment)) {
            Flash::error(__('messages.not_found', ['model' => __('models/investments.singular')]));

            return redirect(route('investments.index'));
        }

        return view('investments.show')->with('investment', $investment);
    }

    /**
     * Show the form for editing the specified Investment.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $investment = $this->investmentRepository->find($id);

        if (empty($investment)) {
            Flash::error(__('messages.not_found', ['model' => __('models/investments.singular')]));

            return redirect(route('investments.index'));
        }

        return view('investments.edit')->with('investment', $investment);
    }

    /**
     * Update the specified Investment in storage.
     *
     * @param int $id
     * @param UpdateInvestmentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInvestmentRequest $request)
    {
        $investment = $this->investmentRepository->find($id);

        if (empty($investment)) {
            Flash::error(__('messages.not_found', ['model' => __('models/investments.singular')]));

            return redirect(route('investments.index'));
        }

        $investment = $this->investmentRepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/investments.singular')]));

        return redirect(route('investments.index'));
    }

    /**
     * Remove the specified Investment from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $investment = $this->investmentRepository->find($id);

        if (empty($investment)) {
            Flash::error(__('messages.not_found', ['model' => __('models/investments.singular')]));

            return redirect(route('investments.index'));
        }

        $this->investmentRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/investments.singular')]));

        return redirect(route('investments.index'));
    }
}
