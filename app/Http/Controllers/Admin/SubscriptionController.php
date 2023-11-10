<?php

namespace App\Http\Controllers\Admin;

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
        $collection = Subscription::with(['packages'])->paginate();
        return view('admin.subscriptions.index', compact('collection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subscriptions,name',
            'name_ur' => 'required|string|max:255|unique:subscriptions,name_ur',
            'description' => 'required|string',
            'description_ur' => 'required|string',
            'type' => 'required|in:email,whatsapp,facebook,instagram,linkedin,twitter',
            'status' => 'required',
        ]);

        Subscription::create($request->all());
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription created successfully.');
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
        $item = Subscription::findOrFail($id);
        return view('admin.subscriptions.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subscriptions,name,' . $id,
            'name_ur' => 'required|string|max:255|unique:subscriptions,name_ur,' . $id,
            'description' => 'required|string',
            'description_ur' => 'required|string',
            'type' => 'required|in:email,whatsapp,facebook,instagram,linkedin,twitter',
        ]);
        if($request->has('active')){
            $request->merge(['active' => true]);
        }else{
            $request->merge(['active' => false]);
        }
        $subscription = Subscription::findOrFail($id);
        $subscription->update($request->all());
        return redirect()->route('admin.subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function exportContacts()  {
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',   
            'Content-Disposition' => 'attachment; filename=contacts.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];
    
        $columns = array("First Name","Last Name","Email","Phone");
    
        $packages = \App\Models\SubscriptionPackage::where('trial', true)->get();
        $rows = [];
        foreach ($packages as $package) {
            foreach($package->users as $user){
                $rows[] = array($user->name, $user->name, $user->email, "+".$user->pivot->contact);
            }
        }
    
        $callback = function() use($rows, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columns);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
}
