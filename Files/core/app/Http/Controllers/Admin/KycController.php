<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Lib\FormProcessor;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function setting()
    {
        $pageTitle = 'KYC Setting';
        $form = Form::where('act','kyc')->first();
        return \App\Lib\InertiaBridge::admin('admin.kyc.setting', compact('pageTitle','form'));
    }

    public function settingUpdate(Request $request)
    {
        $formProcessor = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $request->validate($generatorValidation['rules'],$generatorValidation['messages']);
        $exist = Form::where('act','kyc')->first();
        $formProcessor->generate('kyc',$exist,'act');

        $notify[] = ['success','KYC data updated successfully'];
        return back()->withNotify($notify);
    }

    public function settingBuyer()
    {
        $pageTitle = 'KYC Setting for Buyer';
        $form = Form::where('act','kyc_buyer')->first();
        return \App\Lib\InertiaBridge::admin('admin.kyc.setting_buyer', compact('pageTitle','form'));
    }

    public function settingUpdateBuyer(Request $request)
    {
        $formProcessor = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $request->validate($generatorValidation['rules'],$generatorValidation['messages']);
        $exist = Form::where('act','kyc_buyer')->first();
        $formProcessor->generate('kyc_buyer',$exist,'act');

        $notify[] = ['success','Buyer KYC data updated successfully'];
        return back()->withNotify($notify);
    }
}
