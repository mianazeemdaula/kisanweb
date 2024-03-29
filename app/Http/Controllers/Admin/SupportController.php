<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Support;
class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collection = Support::where('status', 'open')->paginate();
        return view('admin.support.index', compact('collection'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
            'status' => 'required|string|in:open,closed',
        ]);
        $support = Support::findOrfail($id);
        $support->status = $request->status;
        $support->save();
        return redirect()->route('admin.support.index')->with('success', 'Support ticket status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
