<?php

namespace App\Http\Controllers;

use App\Models\ComplainType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ComplainTypeController extends Controller
{
    public function index()
    {
        $complainTypes = ComplainType::orderBy('name')->get();
        return view('complain_types.index', compact('complainTypes'));
    }

    public function create()
    {
        return view('complain_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:complain_types,name',
            'description' => 'nullable|string|max:1000',
        ]);

        ComplainType::create([
            'name' => $request->name,
            'description' => $request->description ?? '',
        ]);

        return redirect()->route('complain-types.index')->with('success', 'Complain category added.');
    }

    public function edit(ComplainType $complainType)
    {
        return view('complain_types.edit', compact('complainType'));
    }

    public function update(Request $request, ComplainType $complainType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:complain_types,name,' . $complainType->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $complainType->update([
            'name' => $request->name,
            'description' => $request->description ?? '',
        ]);

        return redirect()->route('complain-types.index')->with('success', 'Complain category updated.');
    }

    public function destroy(ComplainType $complainType)
    {
        $complainType->delete();

        return redirect()->route('complain-types.index')->with('success', 'Complain category deleted.');
    }
}
