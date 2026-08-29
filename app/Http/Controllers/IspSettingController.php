<?php

namespace App\Http\Controllers;

use App\Models\Isp;
use App\Models\IspSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class IspSettingController extends Controller
{
    /**
     * Display the multi-ISP settings page
     */
    public function index(Request $request)
    {
        $isps = Isp::getAllCached();

        // If no ISPs exist in table, create default Carnival ISP
        if ($isps->isEmpty()) {
            $created = Isp::create([
                'isp_code'            => 'carnival',
                'isp_name'            => 'Carnival Internet',
                'isp_tagline'         => 'High-Speed Broadband Internet & Network Solutions',
                'phone'               => '01XXXXXXXXX',
                'billing_phone'       => '01XXXXXXXXX',
                'email'               => 'support@carnival.com.bd',
                'website'             => 'www.carnival.com.bd',
                'address'             => 'Dhaka, Bangladesh',
                'currency_symbol'     => '৳',
                'invoice_title'       => 'MONEY RECEIPT',
                'payment_instruction' => 'Please pay through bKash/Nagad Merchant or Cash within the due date.',
                'payment_methods'     => 'bKash / Nagad / Cash',
                'payment_number'      => '01XXXXXXXXX',
                'invoice_footer'      => 'Keep this invoice receipt for future reference. Thank you for being with us!',
                'signatory_title'     => 'Authorized Signatory',
                'is_default'          => true,
            ]);
            $isps = collect([$created]);
        }

        // Selected ISP to edit
        if ($request->filled('isp_id')) {
            $selectedIsp = Isp::find($request->isp_id) ?? $isps->first();
        } elseif ($request->filled('isp_code')) {
            $selectedIsp = Isp::forCode($request->isp_code) ?? $isps->first();
        } else {
            $selectedIsp = $isps->firstWhere('is_default', true) ?? $isps->first();
        }

        return view('isp_settings.index', compact('isps', 'selectedIsp'));
    }

    /**
     * Store a newly created ISP profile
     */
    public function store(Request $request)
    {
        $request->validate([
            'isp_code'            => 'required|string|max:50|alpha_dash|unique:isps,isp_code',
            'isp_name'            => 'required|string|max:255',
            'isp_tagline'         => 'nullable|string|max:255',
            'phone'               => 'nullable|string|max:100',
            'billing_phone'       => 'nullable|string|max:100',
            'email'               => 'nullable|email|max:150',
            'website'             => 'nullable|string|max:255',
            'address'             => 'nullable|string|max:500',
            'currency_symbol'     => 'nullable|string|max:10',
            'invoice_title'       => 'nullable|string|max:100',
            'payment_instruction' => 'nullable|string|max:500',
            'payment_methods'     => 'nullable|string|max:255',
            'payment_number'      => 'nullable|string|max:100',
            'invoice_footer'      => 'nullable|string|max:500',
            'signatory_title'     => 'nullable|string|max:100',
            'isp_logo'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        $data = $request->except(['_token', 'isp_logo']);
        $data['isp_code'] = strtolower(trim($data['isp_code']));

        // Handle Logo Upload
        if ($request->hasFile('isp_logo')) {
            $uploadDir = public_path('uploads/settings');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true, true);
            }
            $file = $request->file('isp_logo');
            $filename = 'logo_' . $data['isp_code'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $data['isp_logo'] = $filename;
        }

        $isp = Isp::create($data);
        Isp::clearCache();

        return redirect()->route('isp-settings.index', ['isp_id' => $isp->id])
            ->with('success', 'New ISP profile "' . $isp->isp_name . '" created successfully!');
    }

    /**
     * Update the specified ISP settings
     */
    public function update(Request $request, $id)
    {
        $isp = Isp::findOrFail($id);

        $request->validate([
            'isp_code'            => 'required|string|max:50|alpha_dash|unique:isps,isp_code,' . $isp->id,
            'isp_name'            => 'required|string|max:255',
            'isp_tagline'         => 'nullable|string|max:255',
            'phone'               => 'nullable|string|max:100',
            'billing_phone'       => 'nullable|string|max:100',
            'email'               => 'nullable|email|max:150',
            'website'             => 'nullable|string|max:255',
            'address'             => 'nullable|string|max:500',
            'currency_symbol'     => 'nullable|string|max:10',
            'invoice_title'       => 'nullable|string|max:100',
            'payment_instruction' => 'nullable|string|max:500',
            'payment_methods'     => 'nullable|string|max:255',
            'payment_number'      => 'nullable|string|max:100',
            'invoice_footer'      => 'nullable|string|max:500',
            'signatory_title'     => 'nullable|string|max:100',
            'isp_logo'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
        ]);

        $uploadDir = public_path('uploads/settings');
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true, true);
        }

        $data = $request->except(['_token', '_method', 'isp_logo', 'remove_logo']);
        $data['isp_code'] = strtolower(trim($data['isp_code']));

        // Handle Logo Removal
        if ($request->has('remove_logo') && $request->remove_logo == '1') {
            if ($isp->isp_logo && File::exists($uploadDir . '/' . $isp->isp_logo)) {
                File::delete($uploadDir . '/' . $isp->isp_logo);
            }
            $data['isp_logo'] = null;
        }

        // Handle Logo Upload
        if ($request->hasFile('isp_logo')) {
            $file = $request->file('isp_logo');
            $filename = 'logo_' . $data['isp_code'] . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Delete old uploaded file
            if ($isp->isp_logo && File::exists($uploadDir . '/' . $isp->isp_logo)) {
                File::delete($uploadDir . '/' . $isp->isp_logo);
            }

            $file->move($uploadDir, $filename);
            $data['isp_logo'] = $filename;
        }

        if ($request->has('is_default') && $request->is_default) {
            Isp::where('id', '!=', $isp->id)->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        $isp->update($data);
        Isp::clearCache();

        return redirect()->route('isp-settings.index', ['isp_id' => $isp->id])
            ->with('success', 'ISP settings for "' . $isp->isp_name . '" updated successfully!');
    }

    /**
     * Set ISP as default
     */
    public function setDefault($id)
    {
        $isp = Isp::findOrFail($id);
        Isp::where('id', '!=', $isp->id)->update(['is_default' => false]);
        $isp->update(['is_default' => true]);
        Isp::clearCache();

        return redirect()->route('isp-settings.index', ['isp_id' => $isp->id])
            ->with('success', '"' . $isp->isp_name . '" set as default ISP.');
    }

    /**
     * Delete an ISP profile
     */
    public function destroy($id)
    {
        $isp = Isp::findOrFail($id);

        if ($isp->is_default) {
            return redirect()->back()->with('error', 'Cannot delete the default ISP profile. Please set another ISP as default first.');
        }

        if (Isp::count() <= 1) {
            return redirect()->back()->with('error', 'At least one ISP profile must remain.');
        }

        // Delete logo file if exists
        if ($isp->isp_logo && File::exists(public_path('uploads/settings/' . $isp->isp_logo))) {
            File::delete(public_path('uploads/settings/' . $isp->isp_logo));
        }

        $name = $isp->isp_name;
        $isp->delete();
        Isp::clearCache();

        return redirect()->route('isp-settings.index')
            ->with('success', 'ISP profile "' . $name . '" deleted successfully.');
    }
}
