<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\FeedMill;
use App\Models\City;

class FeedMillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = FeedMill::latest()->paginate();
        return view('admin.feedmills.index', compact('collection'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::orderBy('name')->get();
        return view('admin.feedmills.create', compact('cities'));
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
        $city = new FeedMill;
        $city->city_id = $request->city_id;
        $city->name = $request->name;
        $city->name_ur = $request->name_ur;
        $city->save();
        return redirect()->route('admin.feedmills.index');
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
        $model = FeedMill::findOrFail($id);
        return view('admin.feedmills.edit', compact('cities','model'));
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
        $city = FeedMill::findOrFail($id);
        $city->city_id = $request->city_id;
        $city->name = $request->name;
        $city->name_ur = $request->name_ur;
        $city->save();
        return redirect()->route('admin.feedmills.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop = FeedMill::findOrFail($id);
        $shop->delete();
        return redirect()->back();
    }
}
