<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\LeadCreditService;
use App\Models\GatewayCurrency;
use App\Models\LeadCreditLog;
use App\Models\LeadCreditPackage;
use App\Models\SubscriptionPlan;
use App\Constants\Status;
use Inertia\Inertia;

class LeadCreditController extends Controller
{
    public function index()
    {
        abort_unless(LeadCreditService::isEnabled(), 404);

        $user = auth()->user();
        $summary = LeadCreditService::summaryFor($user);

        $packages = LeadCreditService::creditsModeEnabled()
            ? LeadCreditPackage::active()->get()
            : collect();

        $plans = LeadCreditService::subscriptionModeEnabled()
            ? SubscriptionPlan::active()->get()
            : collect();

        $gateways = GatewayCurrency::whereHas('method', function ($query) {
            $query->where('status', Status::ENABLE);
        })->with('method')->orderBy('name')->get();

        $logs = LeadCreditLog::where('user_id', $user->id)
            ->latest('id')
            ->paginate(getPaginate());

        return Inertia::render('User/LeadCredits', [
            'pageTitle' => 'Lead Credits & Plans',
            'wallet' => [
                'balance' => (float) $user->balance,
                'balance_formatted' => showAmount($user->balance),
                'currency' => gs('cur_text'),
            ],
            'summary' => $summary,
            'packages' => $packages->map(fn ($package) => [
                'id' => $package->id,
                'name' => $package->name,
                'credits' => $package->credits,
                'bonus_credits' => $package->bonus_credits,
                'total_credits' => $package->totalCredits(),
                'price' => showAmount($package->price),
                'price_raw' => (float) $package->price,
            ])->values()->all(),
            'plans' => $plans->map(fn ($plan) => [
                'id' => $plan->id,
                'name' => $plan->name,
                'slug' => $plan->slug,
                'price' => showAmount($plan->price),
                'price_raw' => (float) $plan->price,
                'duration_days' => $plan->duration_days,
                'monthly_credits' => $plan->monthly_credits,
                'unlimited_quotes' => (bool) $plan->unlimited_quotes,
                'description' => $plan->description,
            ])->values()->all(),
            'gateways' => $gateways->map(fn ($gate) => [
                'method_code' => $gate->method_code,
                'name' => $gate->name,
                'currency' => $gate->currency,
                'min_amount' => (float) $gate->min_amount,
                'max_amount' => (float) $gate->max_amount,
            ])->values()->all(),
            'setup' => [
                'credits_mode' => LeadCreditService::creditsModeEnabled(),
                'subscription_mode' => LeadCreditService::subscriptionModeEnabled(),
                'has_packages' => $packages->isNotEmpty(),
                'has_plans' => $plans->isNotEmpty(),
                'has_gateways' => $gateways->isNotEmpty(),
            ],
            'logs' => $logs->through(fn ($log) => [
                'id' => $log->id,
                'credits' => $log->credits,
                'balance_after' => $log->balance_after,
                'remark' => str_replace('_', ' ', $log->remark),
                'created_at' => showDateTime($log->created_at),
            ]),
        ]);
    }
}
