<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cats = Category::with(['categories'])->whereNull('parent_id')->get();
        return response()->json($cats);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'name_ur' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort' => 'nullable|integer',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->name_ur = $request->name_ur;
        $category->parent_id = $request->parent_id;
        $category->icon = $request->icon;
        $category->is_active = $request->is_active ?? true;
        $category->sort = $request->sort ?? 0;
        $category->save();

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subcats = SubCategory::where('category_id', $id)->get();
        return response()->json($subcats);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string',
            'name_ur' => 'sometimes|required|string',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort' => 'nullable|integer',
        ]);

        $category = Category::findOrFail($id);
        if ($request->has('name')) $category->name = $request->name;
        if ($request->has('name_ur')) $category->name_ur = $request->name_ur;
        if ($request->has('parent_id')) $category->parent_id = $request->parent_id;
        if ($request->has('icon')) $category->icon = $request->icon;
        if ($request->has('is_active')) $category->is_active = $request->is_active;
        if ($request->has('sort')) $category->sort = $request->sort;
        $category->save();

        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully', 'status' => true], 200);
    }
}

