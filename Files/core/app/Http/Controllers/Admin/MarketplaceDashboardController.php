<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\AdminResource;
use App\Models\Bid;
use App\Models\Dispute;
use App\Models\Job;
use App\Models\LeadCreditLog;
use App\Models\Project;
use App\Models\ProviderSubscription;
use App\Models\ProviderVerification;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;

class MarketplaceDashboardController extends Controller
{
    public function index()
    {
        $pageTitle = 'Marketplace Dashboard';

        $metrics = [
            'total_requests'        => Job::count(),
            'published_requests'    => Job::where('status', Status::JOB_PUBLISH)->count(),
            'pending_approval'      => Job::pending()->where('status', Status::JOB_PUBLISH)->count(),
            'processing_requests'   => Job::where('status', Status::JOB_PROCESSING)->count(),
            'completed_requests'    => Job::where('status', Status::JOB_COMPLETED)->count(),

            'total_quotes'          => Bid::count(),
            'pending_quotes'        => Bid::pending()->count(),
            'hired_quotes'          => Bid::accepted()->count(),
            'rejected_quotes'       => Bid::rejected()->count(),

            'running_projects'      => Project::where('status', Status::PROJECT_RUNNING)->count(),
            'reported_projects'     => Project::reported()->count(),

            'open_disputes'         => Dispute::active()->count(),
            'resolved_disputes'     => Dispute::resolved()->count(),

            'pending_providers'     => User::pendingProviderApproval()->count(),
            'pending_verifications' => ProviderVerification::where('status', Status::VERIFICATION_PENDING)->count(),
            'pending_reviews'       => Review::where('status', Status::REVIEW_PENDING)->count(),
        ];

        $totalQuotes = max(1, (int) $metrics['total_quotes']);
        $metrics['hire_rate'] = round(((int) $metrics['hired_quotes'] / $totalQuotes) * 100, 1);

        $metrics['quotes_per_request'] = $metrics['total_requests'] > 0
            ? round($metrics['total_quotes'] / $metrics['total_requests'], 1)
            : 0;

        $monetisationEnabled = (bool) gs('monetisation_enabled');
        $metrics['monetisation_enabled'] = $monetisationEnabled;

        if ($monetisationEnabled) {
            $metrics['credit_purchases_30d'] = LeadCreditLog::where('remark', 'credit_purchase')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->sum('credits');
            $metrics['credits_used_30d'] = abs(LeadCreditLog::where('remark', 'quote_submission')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->sum('credits'));
            $metrics['active_subscriptions'] = ProviderSubscription::active()->count();
        }

        $chart = $this->buildActivityChart();
        $recentDisputes = Dispute::with(['buyer', 'user', 'job'])
            ->latest('id')
            ->take(5)
            ->get();
        $recentQuotes = Bid::with(['user', 'job', 'buyer'])
            ->latest('id')
            ->take(8)
            ->get();
        $recentVerifications = ProviderVerification::with('user')
            ->where('status', Status::VERIFICATION_PENDING)
            ->latest('id')
            ->take(5)
            ->get();

        return Inertia::render('Admin/Marketplace/Dashboard', [
            'pageTitle' => $pageTitle,
            'metrics' => AdminResource::marketplaceMetrics($metrics),
            'chart' => AdminResource::activityChart($chart),
            'recentDisputes' => AdminResource::recentDisputes($recentDisputes),
            'recentQuotes' => AdminResource::recentQuotes($recentQuotes),
            'recentVerifications' => AdminResource::recentVerifications($recentVerifications),
        ]);
    }

    protected function buildActivityChart(): array
    {
        $start = Carbon::now()->subDays(29)->startOfDay();
        $end = Carbon::now()->endOfDay();

        $requests = Job::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $quotes = Bid::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $requestSeries = [];
        $quoteSeries = [];
        $cursor = $start->copy();

        while ($cursor <= $end) {
            $key = $cursor->toDateString();
            $labels[] = $cursor->format('d M');
            $requestSeries[] = (int) ($requests[$key] ?? 0);
            $quoteSeries[] = (int) ($quotes[$key] ?? 0);
            $cursor->addDay();
        }

        return [
            'labels'   => $labels,
            'requests' => $requestSeries,
            'quotes'   => $quoteSeries,
        ];
    }
}
