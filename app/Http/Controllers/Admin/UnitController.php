<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeightType;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = WeightType::paginate();
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:weight_types,key',
            'name' => 'required|string',
            'name_ur' => 'required|string',
        ]);
        WeightType::create($data);
        return redirect()->route('admin.units.index')->with('success', 'Unit created successfully');
    }

    public function edit($id)
    {
        $unit = WeightType::findOrFail($id);
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = WeightType::findOrFail($id);
        $data = $request->validate([
            'key' => 'required|string|unique:weight_types,key,' . $unit->id,
            'name' => 'required|string',
            'name_ur' => 'required|string',
        ]);
        $unit->update($data);
        return redirect()->route('admin.units.index')->with('success', 'Unit updated successfully');
    }

    public function destroy($id)
    {
        $unit = WeightType::findOrFail($id);
        $unit->delete();
        return redirect()->route('admin.units.index')->with('success', 'Unit deleted successfully');
    }
}
