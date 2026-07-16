<?php

namespace App\Http\Controllers\User;

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
            ->where('user_id', auth()->id())
            ->with(['job', 'buyer', 'project'])
            ->latest('id')
            ->paginate(getPaginate());

        return Inertia::render('User/Disputes/Index', [
            'pageTitle' => $pageTitle,
            'disputes' => DashboardResource::disputes($disputes),
        ]);
    }

    public function detail($id)
    {
        $pageTitle = 'Dispute Details';
        $dispute = Dispute::query()
            ->where('user_id', auth()->id())
            ->with(['job', 'bid', 'project', 'buyer'])
            ->findOrFail($id);

        return Inertia::render('User/Disputes/Detail', [
            'pageTitle' => $pageTitle,
            'dispute' => DashboardResource::disputeDetail($dispute, 'freelancer'),
        ]);
    }
}
