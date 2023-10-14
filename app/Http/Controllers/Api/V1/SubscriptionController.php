<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $data['subscriptions'] = Subscription::active()->get();
        $data['subscribed'] = $user->subscriptions()->wherePivot('active',1)->get();
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
            'txid' => 'required'
        ]);
        $user = auth()->user();
        $user->subscriptions()->syncWithoutDetaching([$request->subscription_id => [
            'contact' => $request->contact,
            'active' => false,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'payment_tx_id' => $request->txid,
            'payment_gateway_id' => $request->payment_method,
        ]]);
        $data = $user->subscriptions()->wherePivot('subscription_id', $request->subscription_id)->first();
        return response()->json($data, 200);
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
