<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $category = Category::findOrFail($id);
        $cats = SubCategory::where('category_id',$id)->paginate();
        return view('admin.subcategory.index', compact('cats', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('admin.subcategory.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'name_ur' => 'required',
        ]);
        $cat = new SubCategory;
        $cat->name = $request->name;
        $cat->name_ur = $request->name_ur;
        $cat->category_id = $id;
        $cat->save();
        return redirect()->route('admin.category.sub.index',$id);
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
