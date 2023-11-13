<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\SubscriptionPackage;

class SubscriptionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($subscriptionId)
    {
        $collection = Subscription::findOrFail($subscriptionId)->packages()->paginate();
        return view('admin.subscriptions.packages.index', compact('collection', 'subscriptionId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($subscriptionId)
    {
        return view('admin.subscriptions.packages.create', compact('subscriptionId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $subscriptionId)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'name' => 'required|string|max:255|unique:subscription_packages,name',
            'name_ur' => 'required|string|max:255|unique:subscription_packages,name_ur',
            'fee' => 'required|numeric',
            'duration' => 'required|numeric',
            'duration_unit' => 'required|in:day,week,month,year',
            'trial' => 'required|boolean',
            'status' => 'required',
        ]);

        SubscriptionPackage::create($request->all());
        return redirect()->route('admin.subscriptions.packages.index', $subscriptionId)->with('success', 'Subscription Package created successfully.');
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
    public function edit(string $subscriptionId, $id)
    {
        $item = SubscriptionPackage::findOrFail($id);
        return view('admin.subscriptions.packages.edit', compact('item', 'subscriptionId', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$subscriptionId , string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subscription_packages,name,' . $id,
            'name_ur' => 'required|string|max:255|unique:subscription_packages,name_ur,' . $id,
            'fee' => 'required|numeric',
            'duration' => 'required|numeric',
            'duration_unit' => 'required|in:day,week,month,year',
            'trial' => 'sometimes|boolean',
        ]);

        $subscriptionPackage = SubscriptionPackage::findOrFail($id);
        $subscriptionPackage->update($request->all());
        return redirect()->route('admin.subscriptions.packages.index', $subscriptionId)->with('success', 'Subscription Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
