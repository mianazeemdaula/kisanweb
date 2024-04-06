<?php

namespace App\Http\Controllers\Api\V1\SugarMill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SugarMill;
use Carbon\Carbon;
class SugarMillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = SugarMill::with(['rate' => function($q){
            // $q->whereDate('created_at','>=',Carbon::now()->subDay()->format('Y-m-d'));
        },'city'])
        ->whereHas('rate')->paginate(50);
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
