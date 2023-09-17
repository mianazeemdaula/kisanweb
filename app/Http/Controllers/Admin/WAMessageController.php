<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use WaAPI\WaAPI\WaAPI;


class WAMessageController extends Controller
{
    public $wa;

    public function __construct()
    {
        $this->wa = new WaAPI();
    }

    public function getGroups()
    {
        $groups = $this->wa->getGroups();
        return view('admin.whatsapp.groups', compact('groups'));
    }

    public function sendGroupMessage(Request $request)
    {
        $nextMinute = 0;
        foreach ($request->groups as $group) {
            $job = [
                'to' => $group,
                'text' => $request->text,
            ];
            \App\Jobs\ProcessWhatsApp::dispatch($job)
                    ->delay(now()->addMinutes($nextMinute));
            $nextMinute = $nextMinute + 2;
        }
        return response()->json(['message' => 'Message sent successfully'], 200);
    }


    function sendMessage()  {
        return view('admin.whatsapp.send-message');
    }

    public function postSendMessage(Request $request)
    {
        $request->validate([
            'to' => 'required',
            'text' => 'required',
        ]);
        $to = $request->to;
        if(!Str::endsWith($to, '@c.us')){
            $to = $to.'@c.us';
        }
        if(Str::startsWith($to, '03')){
            $to = '92'.substr($to, 1);
        }
        $wapp = new WaAPI();
        if($request->hasFile('media')){
            $fileName = time().'.'.$request->file('media')->getClientOriginalExtension();
            $media = asset($request->file('media')->move('temp',$fileName));
            $wapp->sendMediaFromUrl($to, $media, $request->text, "image");
        }else{
            $wapp->sendMessage($to, $request->text);
        }
        return redirect()->back()->with(['message' => 'Message sent successfully']);
    }
}
