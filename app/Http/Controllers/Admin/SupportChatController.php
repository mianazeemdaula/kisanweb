<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Support;
class SupportChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($supportId)
    {
        $support = Support::findOrfail($supportId);
        $collection = $support->details()->get();
        return view('admin.support.details.index', compact('collection', 'support'));
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
    public function store(Request $request, $supportId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);
        $support = Support::findOrfail($supportId);
        $support->details()->create([
            'content' => $request->message,
            'user_id' => auth()->id(),
        ]);
        return redirect()->back();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
