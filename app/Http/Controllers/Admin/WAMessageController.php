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
        $wapp = new WaAPI();
        $res = $wapp->getChats();
        $groups = [];
        foreach ($res->data as $chat) {
            if($chat['isGroup'] == true && $chat['isReadOnly'] == false){
                $data['id'] = $chat['id']['_serialized'];
                $data['name'] = $chat['name'];
                $groups[] = $data;
            }else{
                $data['id'] = $chat['id']['_serialized'];
                $data['name'] = $chat['name'];
                $chats[] = $data;
            }
        }
        return view('admin.whatsapp.send-group-message', compact('groups'));
    }

    public function sendGroupMessage(Request $request)
    {
        $request->validate([
            'to' => 'required',
            'text' => 'required',
        ]);
        $nextMinute = 0;
        $media = null;
        if($request->hasFile('media')){
            $fileName = time().'.'.$request->file('media')->getClientOriginalExtension();
            $media = asset($request->file('media')->move('temp',$fileName));
        }
        $respones = [];
        foreach ($request->to as $to) {
            $job = [
                'to' => $to,
                'text' => $request->text,
            ];
            if($media){
                $job['media'] = $media;
            }
            \App\Jobs\ProcessWhatsApp::dispatch($job)
                    ->delay(now()->addMinutes($nextMinute));
            $nextMinute = $nextMinute + 2;
            $respones[] = $job;
        }
        // return $respones;
        return redirect()->back();
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

    private function readGroups()  {
        $file_path = public_path('wa_groups.json');
        $groups = [];
        File::exists($file_path);
        if (file_exists($file_path)) {
            $json_data = file_get_contents($file_path);
            $groups = json_decode($json_data, true);
        } else {
            $wapp = new WaAPI();
            $res = $wapp->getChats();
            $groups = [];
            foreach ($res->data as $chat) {
                if($chat['isGroup'] == true && $chat['isReadOnly'] == false && $chat['name'] != null){
                    $data['id'] = $chat['id']['_serialized'];
                    $data['name'] = $chat['name'];
                    $groups[] = $data;
                }else{
                    $data['id'] = $chat['id']['_serialized'];
                    $data['name'] = $chat['name'];
                    $chats[] = $data;
                }
            }
            $json_data = json_encode($groups, JSON_PRETTY_PRINT);
            $file_path = public_path('wa_groups.json');
            file_put_contents($file_path, $json_data);
            $groups = collect($groups);
        }
        return $groups;
    }
}
