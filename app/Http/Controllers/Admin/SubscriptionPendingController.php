<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SubscriptionPackage;
use Illuminate\Support\Str;
use App\Models\User;
use WaAPI\WaAPI\WaAPI;

class SubscriptionPendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collection = SubscriptionPackage::with(['users' => function($q){
            $q->wherePivot('active', false);
        }])->whereHas('users')->paginate();
        return view('admin.subscriptions.pendings.index', compact('collection'));
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
        $package = SubscriptionPackage::findOrFail($id);
        $lastDate = now();
        if($package->duration_unit == 'month'){
            $lastDate = $lastDate->addMonths($package->duration);
        }else if($package->duration_unit == 'year'){
            $lastDate = $lastDate->addYears($package->duration);    
        }else{
            $lastDate = $lastDate->addDays($package->duration);
        }

        $active = $request->status == 'accept';

        $package->users()->updateExistingPivot($request->user_id, [
            'active' => $active,
            'start_date' => now(),
            'end_date' => $lastDate,
        ]);

        $data = $package->users()->wherePivot('user_id', $request->user_id)->first();
        $waapi = new WaAPI();
        $to = $request->contact;
        if(Str::startsWith($to, '03')){
            $to = '92'.substr($to, 1);
        }
        $to = $to."@c.us";
        if($data->pivot->active){
            $waapi->addGroupParticipant("120363168242340048@g.us",$to);
        }else{
            $waapi->removeGroupParticipant("120363168242340048@g.us", $to);
        }
        return redirect()->route('admin.pending-subscriptions.index')->with('success', 'Subscription approved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
