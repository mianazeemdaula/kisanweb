<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $data = $user->subscriptions()->paginate();
        return response()->json($data, 200);
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
            'subscription_id' => 'required|exists:subscriptions,id',
            'payment_method' => 'required|exists:payment_gateways,id',
            'contact' => 'required',
        ]);
        $user = auth()->user();
        $user->subscriptions()->syncWithoutDetaching($request->subscription_id, [
            'payment_gateway_id' => $request->payment_method,
            'contact' => $request->contact,
            'active' => false,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
        ]);
        return response()->json(['message' => 'Subscription added successfully'], 200);
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
