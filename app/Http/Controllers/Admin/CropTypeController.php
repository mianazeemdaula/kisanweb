<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CropType;
use App\Models\Crop;
use Illuminate\Http\Request;

class CropTypeController extends Controller
{
    public function index()
    {
        $cropTypes = CropType::with('crop')->get();
        return view('admin.croptype.index', compact('cropTypes'));
    }

    public function create()
    {
        $crops = Crop::all();
        return view('admin.croptype.create', compact('crops'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'crop_id' => 'required|exists:crops,id',
            'name' => 'required|string',
            'code' => 'nullable|string',
            'sort' => 'integer',
        ]);
        CropType::create($data);
        return redirect()->route('admin.croptype.index')->with('success', 'Crop Type created successfully');
    }

    public function edit(CropType $croptype)
    {
        $crops = Crop::all();
        return view('admin.croptype.edit', compact('croptype', 'crops'));
    }

    public function update(Request $request, CropType $croptype)
    {
        $data = $request->validate([
            'crop_id' => 'required|exists:crops,id',
            'name' => 'required|string',
            'code' => 'nullable|string',
            'sort' => 'integer',
        ]);
        $croptype->update($data);
        return redirect()->route('admin.croptype.index')->with('success', 'Crop Type updated successfully');
    }

    public function destroy(CropType $croptype)
    {
        $croptype->delete();
        return redirect()->route('admin.croptype.index')->with('success', 'Crop Type deleted successfully');
    }
}
