<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\City;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shops = City::latest()->paginate();
        return view('admin.cities.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $districts = District::all();
        return view('admin.cities.create', compact('districts'));
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
            'district' => 'required',
            'name' => 'required',
            'name_ur' => 'required',
        ]);
        $city = new City;
        $city->district_id = $request->district;
        $city->name = $request->name;
        $city->name_ur = $request->name_ur;
        $city->save();
        return redirect()->route('cities.index', ['parameterKey' => 'value']);
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
        $districts = District::all();
        $city = City::findOrFail($id);
        return view('admin.cities.edit', compact('districts', 'city'));
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
            'district' => 'required',
            'name' => 'required',
            'name_ur' => 'required',
        ]);
        $city = City::findOrFail($id);
        $city->district_id = $request->district;
        $city->name = $request->name;
        $city->name_ur = $request->name_ur;
        $city->save();
        return redirect()->route('cities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop = CommissionShop::findOrFail($id);
        $shop->delete();
        return redirect()->back();
    }
}
