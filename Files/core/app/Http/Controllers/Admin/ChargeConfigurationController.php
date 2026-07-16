<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\RequiredConfig;
use App\Models\Charge;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;


class ChargeConfigurationController extends Controller
{
    public function index()
    {
        $pageTitle       = 'Configuration Charge';
        $charges       = Charge::get();
        return \App\Lib\InertiaBridge::admin('admin.setting.charge', compact('pageTitle', 'charges'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'amount'         => 'required',
            'amount*'        => 'required|numeric',
            'percent'         => 'required',
            'percent*'        => 'required|numeric',
        ]);

        Charge::truncate();

        for ($i = 0; $i < count($request->percent); $i++) {
            $charge                  = new Charge();
            $charge->level           = $i + 1;
            $charge->amount         = $request->amount[$i];
            $charge->percent         = $request->percent[$i];
            $charge->save();
        }

        RequiredConfig::configured('charge');

        $notify[] = ['success', 'Charge configured successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return GeneralSetting::changeStatus($id, 'percent_service_charge');
    }
}