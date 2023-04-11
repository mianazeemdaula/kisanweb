<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home.index');
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
