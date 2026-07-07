<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcats = SubCategory::with('category')->get();
        return response()->json($subcats, 200);
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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'name_ur' => 'required|string',
            'slug' => 'nullable|string|unique:sub_categories,slug',
            'icon' => 'nullable|string',
        ]);

        $subcat = new SubCategory();
        $subcat->category_id = $request->category_id;
        $subcat->name = $request->name;
        $subcat->name_ur = $request->name_ur;
        $subcat->slug = $request->slug;
        $subcat->icon = $request->icon;
        $subcat->save();

        return response()->json($subcat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subcat = SubCategory::with('category')->findOrFail($id);
        return response()->json($subcat, 200);
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
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string',
            'name_ur' => 'sometimes|required|string',
            'slug' => 'nullable|string|unique:sub_categories,slug,' . $id,
            'icon' => 'nullable|string',
        ]);

        $subcat = SubCategory::findOrFail($id);
        if ($request->has('category_id')) $subcat->category_id = $request->category_id;
        if ($request->has('name')) $subcat->name = $request->name;
        if ($request->has('name_ur')) $subcat->name_ur = $request->name_ur;
        if ($request->has('slug')) $subcat->slug = $request->slug;
        if ($request->has('icon')) $subcat->icon = $request->icon;
        $subcat->save();

        return response()->json($subcat, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subcat = SubCategory::findOrFail($id);
        $subcat->delete();
        return response()->json(['message' => 'Subcategory deleted successfully', 'status' => true], 200);
    }
}

