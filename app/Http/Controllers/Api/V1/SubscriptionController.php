<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WaAPI\WaAPI\WaAPI;
use App\Models\Subscription;
use Illuminate\Support\Str;
use App\Models\SubscriptionPackage;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $data['subscriptions'] = Subscription::active()->with(['packages'])
        ->whereHas('packages')->get();
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
            'package_id' => 'required|exists:subscription_packages,id',
            'payment_method' => 'required|exists:payment_gateways,id',
            'contact' => 'required',
            'screenshot' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = auth()->user();
        $data = $user->subscriptions()->wherePivot('subscription_package_id', $request->package_id)->first();

        if($data){
            $isExpired = Carbon::parse($data->pivot->end_date)->isPast();
            if($data->trial){
                return response()->json(['message' => "You are already avail the trial"], 422);
            }else if(!$isExpired){
                return response()->json(['message' => "You are already subscribed to this package"], 422);
            }
        }

        $package = SubscriptionPackage::find($request->package_id);
        $lastDate = now();
        if($package->duration_unit == 'month'){
            $lastDate = $lastDate->addMonths($package->duration);
        }else if($package->duration_unit == 'year'){
            $lastDate = $lastDate->addYears($package->duration);    
        }else{
            $lastDate = $lastDate->addDays($package->duration);
        }
        $screenshot = null;
        if($request->hasFile('screenshot')){
            $screenshot = $request->file('screenshot')->store('public/subscriptions');
        }
        $user->subscriptions()->syncWithoutDetaching([$request->package_id => [
            'contact' => $request->contact,
            'active' => $package->trial,
            'start_date' => now(),
            'end_date' => $lastDate,
            'payment_tx_id' => $request->txid ?? null,
            'payment_gateway_id' => $request->payment_method,
            'screenshot' => $screenshot ?? null,
        ]]);
        $data = $user->subscriptions()->wherePivot('subscription_package_id', $request->package_id)->first();
        if($data->pivot->active){
            $waapi = new WaAPI();
            $to = $request->contact;
            if(Str::startsWith($to, '03')){
                $to = '92'.substr($to, 1);
            }
            $waapi->addGroupParticipant("120363168242340048@g.us",$to."@c.us");
        }
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
