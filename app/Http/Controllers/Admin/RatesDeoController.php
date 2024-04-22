<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CropRate;
use App\Models\User;
class RatesDeoController extends Controller
{
    public function index(){
        $date = now();
        $userIds = CropRate::whereDate('rate_date', $date)->distinct()->get(['user_id']);
        $users = User::whereIn('id', $userIds)->withCount(['croprates as rate_count' => function($q) use($date){
            $q->whereDate('rate_date',$date);
        }])->paginate();
        return view('admin.deo_rates.index', compact('users', 'date'));
    }

    public function store(Request $request) {
        $request->validate([
            'date' => 'required',
        ]);
        $date = $request->date;
        $userIds = CropRate::whereDate('rate_date', $date)->distinct()->get(['user_id']);
        $users = User::whereIn('id', $userIds)->withCount(['croprates as rate_count' => function($q) use($date){
            $q->whereDate('rate_date',$date);
        }])->paginate();
        return view('admin.deo_rates.index', compact('users', 'date'));
    }
}
