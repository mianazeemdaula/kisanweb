<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $collection = Setting::latest()->paginate();
        return view('admin.settings.index', compact('collection'));
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:settings',
            'type' => 'required',
            'default' => 'required',
        ]);
        $quote = new Setting;
        $quote->name = $request->name;
        $quote->type = $request->type;
        $quote->default = $request->default;
        $quote->is_enabled = $request->is_enabled ?? true;
        $quote->save();
        return redirect()->route('admin.settings.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $model = Setting::findOrFail($id);
        return view('admin.settings.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:settings',
            'type' => 'required',
            'default' => 'required',
        ]);
        $quote = Setting::findOrFail($id);
        $quote->name = $request->name;
        $quote->type = $request->type;
        $quote->default = $request->default;
        $quote->is_enabled = $request->is_enabled ?? true;
        $quote->save();
        return redirect()->route('admin.settings.index');
    }
    
    public function destroy($id)
    {
        $qutoe = Setting::findOrFail($id);
        $qutoe->delete();
        return redirect()->back();
    }
}
