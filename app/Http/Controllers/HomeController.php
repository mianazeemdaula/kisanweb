<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class HomeController extends Controller
{
    public function index()
    {
        $data['users'] = \App\Models\User::whereHas('addresses')->count();
        $data['last_day'] = \App\Models\User::whereHas('addresses')->whereDate('created_at',now()->subDays(1))->count();
        $data['today'] = \App\Models\User::whereHas('addresses')->whereDate('created_at',now())->count();
        $data['deals'] = \App\Models\Deal::where('status','open')->count();
        $data['today_deals'] = \App\Models\Deal::where('status','open')->whereDate('created_at',now())->count();
        $data['feed'] = \App\Models\Feed::count();
        $data['shops'] = \App\Models\CommissionShop::count();
        $data['today_shops'] = \App\Models\CommissionShop::whereDate('created_at',now())->count();
        return view('admin.home.index', compact('data'));
    }
    

    public function newsNotification()
    {
        return view('admin.news-send');
    }

    public function sendNewsNotification(Request $request)
    {
        $tokens = \App\Models\User::whereNotNull('fcm_token')->pluck('fcm_token');
        $data = array();
        foreach ($tokens->chunk(1000) as $value) {
            $keys = $value->toArray();
            $data[] =  \App\Helper\FCM::send($keys, $request->title, $request->body,['type' => 'news', 'crop_id' => 2]);
        }
        return response()->json($data, 200);
    }
}
