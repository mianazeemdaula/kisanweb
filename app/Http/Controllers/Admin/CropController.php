<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;

class CropController extends Controller
{
    public function index()
    {
        $crops = Crop::with('types')->get();
        return view('admin.crop.index', compact('crops'));
    }

    public function create()
    {
        return view('admin.crop.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'name_ur' => 'required|string',
            'icon' => 'required|string',
            'color' => 'required|string',
            'active' => 'boolean',
            'sort' => 'integer',
        ]);
        Crop::create($data);
        return redirect()->route('admin.crop.index')->with('success', 'Crop created successfully');
    }

    public function edit(Crop $crop)
    {
        return view('admin.crop.edit', compact('crop'));
    }

    public function update(Request $request, Crop $crop)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'name_ur' => 'required|string',
            'icon' => 'required|string',
            'color' => 'required|string',
            'active' => 'boolean',
            'sort' => 'integer',
        ]);
        $crop->update($data);
        return redirect()->route('admin.crop.index')->with('success', 'Crop updated successfully');
    }

    public function destroy(Crop $crop)
    {
        $crop->delete();
        return redirect()->route('admin.crop.index')->with('success', 'Crop deleted successfully');
    }
}
