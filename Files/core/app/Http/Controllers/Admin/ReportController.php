<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\AdminResource;
use App\Models\Bid;
use App\Models\Buyer;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function transaction(Request $request, $userId = null)
    {
        $buyerId     = $request->buyer_id ?? null;
        $pageTitle    = 'Transaction Logs';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = $this->getTransactions($userId, $buyerId);

        return Inertia::render('Admin/Reports/Transactions', [
            'pageTitle' => $pageTitle,
            'transactions' => AdminResource::transactions($transactions, $remarks),
        ]);
    }

    protected function getTransactions($userId = null, $buyerId = null)
    {
        $query = Transaction::searchable(['trx', 'user:username', 'buyer:username'])
            ->filter(['trx_type', 'remark'])
            ->dateFilter()
            ->orderBy('id', 'desc')
            ->with('user', 'buyer');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($buyerId) {
            $query->where('buyer_id', $buyerId);
        }

        return $query->paginate(getPaginate());
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $loginLogs = UserLogin::orderBy('id', 'desc')->searchable(['user:username', 'buyer:username'])->dateFilter()->with('user', 'buyer')->paginate(getPaginate());
        return \App\Lib\InertiaBridge::admin('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user', 'buyer')->paginate(getPaginate());
        return \App\Lib\InertiaBridge::admin('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs      = NotificationLog::orderBy('id', 'desc')->searchable(['user:username', 'buyer:username'])->dateFilter()->with('user', 'buyer')->paginate(getPaginate());
        return \App\Lib\InertiaBridge::admin('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email     = NotificationLog::findOrFail($id);
        return \App\Lib\InertiaBridge::admin('admin.reports.email_details', compact('pageTitle', 'email'));
    }

    public function financialAnalytics()
    {
        $pageTitle          = 'Project Financial Analytics';
        $revenueMetrics     = $this->getRevenueMetrics();
        $freelancerEarnings = User::active()
            ->select([
                'id',
                'firstname',
                'lastname',
                'username',
                DB::raw('(
                    SELECT SUM(bids.bid_amount)
                    FROM bids
                    JOIN projects ON projects.id = bids.project_id
                    WHERE projects.user_id = users.id
                    AND projects.status = 2
                ) as total_earning'),
            ])
            ->havingRaw('total_earning IS NOT NULL')
            ->orderByDesc('total_earning')
            ->take(10)
            ->get();

        $buyerPayments = Buyer::active()
            ->select([
                'id',
                'firstname',
                'lastname',
                'username',
                DB::raw('(
                    SELECT SUM(bids.bid_amount)
                    FROM bids
                    JOIN projects ON projects.id = bids.project_id
                    WHERE projects.buyer_id = buyers.id
                    AND projects.status = 2
                ) as total_paid'),
            ])
            ->havingRaw('total_paid IS NOT NULL')
            ->orderByDesc('total_paid')
            ->take(10)
            ->get();

        $monthlyData = $this->getMonthlyIncomeData();

        return \App\Lib\InertiaBridge::admin('admin.reports.analytics', compact('pageTitle', 'revenueMetrics', 'freelancerEarnings', 'buyerPayments', 'monthlyData'));
    }

    protected function getMonthlyIncomeData()
    {
        $startDate   = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate     = Carbon::now()->endOfMonth();
        $monthlyData = $this->getMonthlyBidData($startDate, $endDate);
        return response()->json($monthlyData->toArray());
    }

    public function financialDateFilter(Request $request)
    {
        $request->validate([
            'date' => 'required|string',
        ]);

        $dates     = explode(' - ', $request->date);
        $startDate = Carbon::parse($dates[0])->startOfDay();
        $endDate   = Carbon::parse($dates[1])->endOfDay();

        $monthlyData = $this->getMonthlyBidData($startDate, $endDate);

        return response()->json([
            'status' => 'success',
            'data'   => $monthlyData,
        ]);
    }

    protected function getMonthlyBidData($startDate, $endDate)
    {
        $dateDiffInDays = $startDate->diffInDays($endDate);
        $groupByDay = $dateDiffInDays < 60;
    
        // Determine the grouping format
        $selectRaw = $groupByDay
            ? 'DATE(projects.uploaded_at) as date, SUM(bids.bid_amount) as total_bid'
            : 'YEAR(projects.uploaded_at) as year, MONTH(projects.uploaded_at) as month, SUM(bids.bid_amount) as total_bid';
    
        $groupBy = $groupByDay
            ? DB::raw('DATE(projects.uploaded_at)')
            : DB::raw('YEAR(projects.uploaded_at), MONTH(projects.uploaded_at)');
    
        // Fetch bid data
        $bids = Bid::join('projects', 'projects.id', '=', 'bids.project_id')
            ->where('projects.status', Status::PROJECT_COMPLETED)
            ->whereBetween('projects.uploaded_at', [$startDate, $endDate])
            ->selectRaw($selectRaw)
            ->groupBy($groupBy)
            ->get()
            ->keyBy(fn($item) => $groupByDay 
                ? $item->date 
                : ($item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT))
            );
    
        // Generate data collection
        $dataCollection = collect();
        $tempDate = $startDate->copy();
    
        while ($tempDate <= $endDate) {
            if ($groupByDay) {
                $key = $tempDate->toDateString();
                $formattedDate = $tempDate->format('d M Y');
                $tempDate->addDay();
            } else {
                $key = $tempDate->year . '-' . str_pad($tempDate->month, 2, '0', STR_PAD_LEFT);
                $formattedDate = $tempDate->format('M Y');
                $tempDate->addMonth();
            }
    
            $totalBid = $bids->get($key, (object) ['total_bid' => 0])->total_bid;
    
            $dataCollection->push([
                'date'      => $formattedDate,
                'total_bid' => $totalBid,
            ]);
        }
    
        return $dataCollection;
    }
    

    protected function getRevenueData($startDate, $endDate)
    {
        return Bid::join('projects', 'projects.id', '=', 'bids.project_id')
            ->where('projects.status', Status::PROJECT_COMPLETED)
            ->whereBetween('projects.uploaded_at', [$startDate, $endDate])
            ->sum('bids.bid_amount');
    }

    protected function getRevenueMetrics()
    {
        $todayRevenue = $this->getRevenueData(
            Carbon::now()->startOfDay(),
            Carbon::now()->endOfDay()
        );

        $last7DaysRevenue = $this->getRevenueData(
            Carbon::now()->subDays(7)->startOfDay(),
            Carbon::now()->endOfDay()
        );

        $last30DaysRevenue = $this->getRevenueData(
            Carbon::now()->subDays(30)->startOfDay(),
            Carbon::now()->endOfDay()
        );

        $thisYearRevenue = $this->getRevenueData(
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfDay()
        );

        $totalRevenue = $this->getRevenueData(
            Carbon::createFromTimestamp(0),
            Carbon::now()->endOfDay()
        );

        return [
            'todayRevenue'      => $todayRevenue,
            'last7DaysRevenue'  => $last7DaysRevenue,
            'last30DaysRevenue' => $last30DaysRevenue,
            'thisYearRevenue'   => $thisYearRevenue,
            'totalRevenue'      => $totalRevenue,
        ];
    }
}
