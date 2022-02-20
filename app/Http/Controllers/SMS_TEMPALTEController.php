<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSMS_TEMPALTERequest;
use App\Http\Requests\UpdateSMS_TEMPALTERequest;
use App\Repositories\SMS_TEMPALTERepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class SMS_TEMPALTEController extends AppBaseController
{
    /** @var  SMS_TEMPALTERepository */
    private $sMSTEMPALTERepository;

    public function __construct(SMS_TEMPALTERepository $sMSTEMPALTERepo)
    {
        $this->sMSTEMPALTERepository = $sMSTEMPALTERepo;
    }

    /**
     * Display a listing of the SMS_TEMPALTE.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $sMSTEMPALTES = $this->sMSTEMPALTERepository->all();

        return view('s_m_s__t_e_m_p_a_l_t_e_s.index')
            ->with('sMSTEMPALTES', $sMSTEMPALTES);
    }

    /**
     * Show the form for creating a new SMS_TEMPALTE.
     *
     * @return Response
     */
    public function create()
    {
        return view('s_m_s__t_e_m_p_a_l_t_e_s.create');
    }

    /**
     * Store a newly created SMS_TEMPALTE in storage.
     *
     * @param CreateSMS_TEMPALTERequest $request
     *
     * @return Response
     */
    public function store(CreateSMS_TEMPALTERequest $request)
    {
        $input = $request->all();

        $sMSTEMPALTE = $this->sMSTEMPALTERepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/sMSTEMPALTES.singular')]));

        return redirect(route('sMSTEMPALTES.index'));
    }

    /**
     * Display the specified SMS_TEMPALTE.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $sMSTEMPALTE = $this->sMSTEMPALTERepository->find($id);

        if (empty($sMSTEMPALTE)) {
            Flash::error(__('messages.not_found', ['model' => __('models/sMSTEMPALTES.singular')]));

            return redirect(route('sMSTEMPALTES.index'));
        }

        return view('s_m_s__t_e_m_p_a_l_t_e_s.show')->with('sMSTEMPALTE', $sMSTEMPALTE);
    }

    /**
     * Show the form for editing the specified SMS_TEMPALTE.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $sMSTEMPALTE = $this->sMSTEMPALTERepository->find($id);

        if (empty($sMSTEMPALTE)) {
            Flash::error(__('messages.not_found', ['model' => __('models/sMSTEMPALTES.singular')]));

            return redirect(route('sMSTEMPALTES.index'));
        }

        return view('s_m_s__t_e_m_p_a_l_t_e_s.edit')->with('sMSTEMPALTE', $sMSTEMPALTE);
    }

    /**
     * Update the specified SMS_TEMPALTE in storage.
     *
     * @param int $id
     * @param UpdateSMS_TEMPALTERequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSMS_TEMPALTERequest $request)
    {
        $sMSTEMPALTE = $this->sMSTEMPALTERepository->find($id);

        if (empty($sMSTEMPALTE)) {
            Flash::error(__('messages.not_found', ['model' => __('models/sMSTEMPALTES.singular')]));

            return redirect(route('sMSTEMPALTES.index'));
        }

        $sMSTEMPALTE = $this->sMSTEMPALTERepository->update($request->all(), $id);

        Flash::success(__('messages.updated', ['model' => __('models/sMSTEMPALTES.singular')]));

        return redirect(route('sMSTEMPALTES.index'));
    }

    /**
     * Remove the specified SMS_TEMPALTE from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $sMSTEMPALTE = $this->sMSTEMPALTERepository->find($id);

        if (empty($sMSTEMPALTE)) {
            Flash::error(__('messages.not_found', ['model' => __('models/sMSTEMPALTES.singular')]));

            return redirect(route('sMSTEMPALTES.index'));
        }

        $this->sMSTEMPALTERepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/sMSTEMPALTES.singular')]));

        return redirect(route('sMSTEMPALTES.index'));
    }
}
