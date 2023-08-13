<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\SugarMill;
use App\Models\City;

class SugarMillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = SugarMill::latest()->paginate();
        return view('admin.sugarmills.index', compact('collection'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        return view('admin.sugarmills.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'name' => 'required',
            'name_ur' => 'required',
        ]);
        $city = new SugarMill;
        $city->city_id = $request->city_id;
        $city->name = $request->name;
        $city->name_ur = $request->name_ur;
        $city->save();
        return redirect()->route('admin.sugarmills.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cities = City::all();
        $model = SugarMill::findOrFail($id);
        return view('admin.sugarmills.edit', compact('cities','model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'city_id' => 'required',
            'name' => 'required',
            'name_ur' => 'required',
        ]);
        $city = SugarMill::findOrFail($id);
        $city->city_id = $request->city_id;
        $city->name = $request->name;
        $city->name_ur = $request->name_ur;
        $city->save();
        return redirect()->route('admin.sugarmills.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop = SugarMill::findOrFail($id);
        $shop->delete();
        return redirect()->back();
    }
}
