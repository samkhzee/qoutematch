<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\AdminResource;
use App\Lib\LeadCreditService;
use App\Models\GeneralSetting;
use App\Models\LeadCreditPackage;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonetisationController extends Controller
{
    public function settings()
    {
        $pageTitle = 'Monetisation Settings';
        $general = GeneralSetting::first();

        return Inertia::render('Admin/Monetisation/Settings', [
            'pageTitle' => $pageTitle,
            'settings' => AdminResource::monetisationSettings($general),
        ]);
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'monetisation_enabled' => 'nullable|in:0,1',
            'monetisation_mode' => 'required|in:credits,subscription,both',
            'quote_credit_cost' => 'required|integer|min:1',
            'provider_welcome_credits' => 'required|integer|min:0',
        ]);

        $general = GeneralSetting::first();
        $general->monetisation_enabled = $request->boolean('monetisation_enabled') ? 1 : 0;
        $general->monetisation_mode = $request->monetisation_mode;
        $general->quote_credit_cost = $request->quote_credit_cost;
        $general->provider_welcome_credits = $request->provider_welcome_credits;
        $general->save();

        $notify[] = ['success', 'Monetisation settings updated successfully'];
        return back()->withNotify($notify);
    }

    public function packages()
    {
        $pageTitle = 'Lead Credit Packages';
        $packages = LeadCreditPackage::orderBy('sort_order')->orderBy('id')->paginate(getPaginate());

        return Inertia::render('Admin/Monetisation/Packages', [
            'pageTitle' => $pageTitle,
            'packages' => AdminResource::creditPackages($packages),
        ]);
    }

    public function packageStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'credits' => 'required|integer|min:1',
            'bonus_credits' => 'nullable|integer|min:0',
            'price' => 'required|numeric|gt:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $package = new LeadCreditPackage();
        $package->name = $request->name;
        $package->credits = $request->credits;
        $package->bonus_credits = $request->bonus_credits ?? 0;
        $package->price = $request->price;
        $package->sort_order = $request->sort_order ?? 0;
        $package->status = Status::ENABLE;
        $package->save();

        $notify[] = ['success', 'Credit package created'];
        return back()->withNotify($notify);
    }

    public function packageUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'credits' => 'required|integer|min:1',
            'bonus_credits' => 'nullable|integer|min:0',
            'price' => 'required|numeric|gt:0',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $package = LeadCreditPackage::findOrFail($id);
        $package->name = $request->name;
        $package->credits = $request->credits;
        $package->bonus_credits = $request->bonus_credits ?? 0;
        $package->price = $request->price;
        $package->sort_order = $request->sort_order ?? 0;
        $package->save();

        $notify[] = ['success', 'Credit package updated'];
        return back()->withNotify($notify);
    }

    public function packageStatus($id)
    {
        return LeadCreditPackage::changeStatus($id);
    }

    public function packageDelete($id)
    {
        LeadCreditPackage::findOrFail($id)->delete();
        $notify[] = ['success', 'Credit package deleted'];
        return back()->withNotify($notify);
    }

    public function plans()
    {
        $pageTitle = 'Subscription Plans';
        $plans = SubscriptionPlan::orderBy('sort_order')->orderBy('id')->paginate(getPaginate());

        return Inertia::render('Admin/Monetisation/Plans', [
            'pageTitle' => $pageTitle,
            'plans' => AdminResource::subscriptionPlans($plans),
        ]);
    }

    public function planStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:60|alpha_dash|unique:subscription_plans,slug',
            'price' => 'required|numeric|gt:0',
            'duration_days' => 'required|integer|min:1',
            'monthly_credits' => 'nullable|integer|min:0',
            'unlimited_quotes' => 'nullable|in:0,1',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $plan = new SubscriptionPlan();
        $plan->name = $request->name;
        $plan->slug = $request->slug;
        $plan->price = $request->price;
        $plan->duration_days = $request->duration_days;
        $plan->monthly_credits = $request->monthly_credits ?? 0;
        $plan->unlimited_quotes = $request->boolean('unlimited_quotes') ? 1 : 0;
        $plan->description = $request->description;
        $plan->sort_order = $request->sort_order ?? 0;
        $plan->status = Status::ENABLE;
        $plan->save();

        $notify[] = ['success', 'Subscription plan created'];
        return back()->withNotify($notify);
    }

    public function planUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:60|alpha_dash|unique:subscription_plans,slug,' . $id,
            'price' => 'required|numeric|gt:0',
            'duration_days' => 'required|integer|min:1',
            'monthly_credits' => 'nullable|integer|min:0',
            'unlimited_quotes' => 'nullable|in:0,1',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $plan = SubscriptionPlan::findOrFail($id);
        $plan->name = $request->name;
        $plan->slug = $request->slug;
        $plan->price = $request->price;
        $plan->duration_days = $request->duration_days;
        $plan->monthly_credits = $request->monthly_credits ?? 0;
        $plan->unlimited_quotes = $request->boolean('unlimited_quotes') ? 1 : 0;
        $plan->description = $request->description;
        $plan->sort_order = $request->sort_order ?? 0;
        $plan->save();

        $notify[] = ['success', 'Subscription plan updated'];
        return back()->withNotify($notify);
    }

    public function planStatus($id)
    {
        return SubscriptionPlan::changeStatus($id);
    }

    public function planDelete($id)
    {
        SubscriptionPlan::findOrFail($id)->delete();
        $notify[] = ['success', 'Subscription plan deleted'];
        return back()->withNotify($notify);
    }

    public function grantCredits(Request $request, $userId)
    {
        $request->validate([
            'credits' => 'required|integer|not_in:0',
            'note' => 'nullable|string|max:120',
        ]);

        $user = User::findOrFail($userId);
        LeadCreditService::adminGrant($user, (int) $request->credits, $request->note ?: 'admin_grant');

        $notify[] = ['success', 'Lead credits updated for provider'];
        return back()->withNotify($notify);
    }
}
