<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\RequiredConfig;
use App\Models\BadgeSetting;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class BadgeConfigurationController extends Controller
{
    public function index()
    {
        $pageTitle  = 'All Badge Settings';
        $badges = BadgeSetting::searchable(['name'])->orderBy('min_amount')->paginate(getPaginate());
        return \App\Lib\InertiaBridge::admin('admin.setting.badge', compact('pageTitle', 'badges'));
    }

    public function store(Request $request, $id = 0)
    {
        $imageValidation = $id ? 'nullable' : 'required';
        $request->validate(
            [
                'badge_name'  => 'required',
                'min_amount'  => 'required|numeric|min:0',
                'image' => ["$imageValidation", new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            ]
        );

        $existingBadge = BadgeSetting::where('min_amount', $request->min_amount)->first();

        if ($existingBadge && $existingBadge->id != $id) {
            $notify[] = ['error', 'A badge amount already exists'];
            return back()->withNotify($notify)->withInput();
        }

        if ($id) {
            $badge     = BadgeSetting::findOrFail($id);
            $notification = 'Badge updated successfully';
        } else {
            $badge     = new BadgeSetting();
            $notification = 'Badge added successfully';
        }
        if ($request->hasFile('image')) {
            try {
                $badge->image = fileUploader($request->image, getFilePath('badge'), getFileSize('badge'), @$badge->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload badge image'];
                return back()->withNotify($notify);
            }
        }

        $badge->badge_name = $request->badge_name;
        $badge->min_amount = $request->min_amount;
        $badge->save();

        RequiredConfig::configured('badge');

        $notify[] = ['success',  $notification];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $badge = BadgeSetting::findOrFail($id);
        $badge->delete();
        $notify[] = ['success', 'Badge deleted successfully'];
        return back()->withNotify($notify);
    }
}