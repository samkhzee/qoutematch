<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PusherSettingController extends Controller
{
    public function pusherSetting(){
        $pageTitle = 'Pusher Settings';
        return \App\Lib\InertiaBridge::admin('admin.pusher.setting', compact('pageTitle'));
    }

    public function pusherSettingUpdate(Request $request){

        $request->validate([
            'app_key' => 'required',
            'app_id' => 'required',
            'app_secret_key' => 'required',
            'cluster'=>'required'
        ]);

        $data = [
            'app_key' => $request->app_key,
            'app_id' => $request->app_id,
            'app_secret_key' => $request->app_secret_key,
            'cluster' => $request->cluster,
        ];

        $general = gs();
        $general->pusher_config = $data;

        $general->save();

        $notify[] = ['success', 'Pusher settings updated successfully'];
        return back()->withNotify($notify);
    }
}
