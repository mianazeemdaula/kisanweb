<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = Category::whereNull('parent_id')->with(['categories'])->paginate();
        return view('admin.category.index', compact('cats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats = Category::whereNull('parent_id')->get();
        return view('admin.category.create', compact('cats'));
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
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required',
            'name_ur' => 'required',
        ]);
        $cat = new Category;
        $cat->parent_id = $request->parent_id;
        $cat->name = $request->name;
        $cat->name_ur = $request->name_ur;
        $cat->save();
        return redirect()->route('admin.category.index');
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
        $cats = Categroy::whereNull('parent_id')->all();
        $category = Categroy::findOrFail($id);
        return view('admin.category.edit', compact('category', 'cats'));
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
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required',
            'name_ur' => 'required',
        ]);
        $cat = Categroy::findOrFail($id);
        $cat->parent_id = $request->parent_id;
        $cat->name = $request->name;
        $cat->name_ur = $request->name_ur;
        $cat->save();
        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();
        return redirect()->back();
    }
}
