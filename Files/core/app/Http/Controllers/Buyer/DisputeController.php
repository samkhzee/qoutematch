<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Lib\DashboardResource;
use App\Models\Dispute;
use Inertia\Inertia;

class DisputeController extends Controller
{
    public function index()
    {
        $pageTitle = 'My Disputes';
        $disputes = Dispute::query()
            ->where('buyer_id', auth()->guard('buyer')->id())
            ->with(['job', 'user', 'project'])
            ->latest('id')
            ->paginate(getPaginate());

        return Inertia::render('Buyer/Disputes/Index', [
            'pageTitle' => $pageTitle,
            'disputes' => DashboardResource::disputes($disputes),
        ]);
    }

    public function detail($id)
    {
        $pageTitle = 'Dispute Details';
        $dispute = Dispute::query()
            ->where('buyer_id', auth()->guard('buyer')->id())
            ->with(['job', 'bid', 'project', 'user'])
            ->findOrFail($id);

        return Inertia::render('Buyer/Disputes/Detail', [
            'pageTitle' => $pageTitle,
            'dispute' => DashboardResource::disputeDetail($dispute, 'buyer'),
        ]);
    }
}
