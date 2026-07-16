<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\AdminResource;
use App\Lib\DisputeService;
use App\Models\Dispute;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DisputeController extends Controller
{
    public function index()
    {
        $pageTitle = 'Disputes';
        $status = request()->get('status', 'active');

        $disputes = Dispute::query()
            ->with(['job', 'bid', 'project', 'buyer', 'user'])
            ->when($status === 'active', fn ($query) => $query->active())
            ->when($status === 'open', fn ($query) => $query->open())
            ->when($status === 'in_review', fn ($query) => $query->inReview())
            ->when($status === 'resolved', fn ($query) => $query->resolved())
            ->when($status === 'rejected', fn ($query) => $query->rejected())
            ->searchable(['subject', 'buyer:username', 'user:username', 'job:title'])
            ->dateFilter()
            ->latest('id')
            ->paginate(getPaginate());

        return Inertia::render('Admin/Disputes/Index', [
            'pageTitle' => $pageTitle,
            'disputes' => AdminResource::disputes($disputes, $status),
        ]);
    }

    public function detail($id)
    {
        $pageTitle = 'Dispute Detail';
        $dispute = Dispute::with(['job', 'bid', 'project', 'buyer', 'user'])->findOrFail($id);

        DisputeService::markAdminAlertsReviewed($dispute);

        return Inertia::render('Admin/Disputes/Detail', [
            'pageTitle' => $pageTitle,
            'dispute' => AdminResource::disputeDetail($dispute),
        ]);
    }

    public function inReview(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $dispute = Dispute::findOrFail($id);

        if (!in_array((int) $dispute->status, [Status::DISPUTE_OPEN, Status::DISPUTE_IN_REVIEW], true)) {
            $notify[] = ['error', 'This dispute is already closed.'];
            return back()->withNotify($notify);
        }

        DisputeService::markInReview($dispute, $request->admin_note);

        $notify[] = ['success', 'Dispute marked as in review.'];
        return back()->withNotify($notify);
    }

    public function resolve(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $dispute = Dispute::findOrFail($id);

        if (in_array((int) $dispute->status, [Status::DISPUTE_RESOLVED, Status::DISPUTE_REJECTED], true)) {
            $notify[] = ['error', 'This dispute is already closed.'];
            return back()->withNotify($notify);
        }

        DisputeService::resolve($dispute, (int) auth()->guard('admin')->id(), $request->admin_note);

        $notify[] = ['success', 'Dispute resolved successfully.'];
        return back()->withNotify($notify);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:2000',
        ]);

        $dispute = Dispute::findOrFail($id);

        if (in_array((int) $dispute->status, [Status::DISPUTE_RESOLVED, Status::DISPUTE_REJECTED], true)) {
            $notify[] = ['error', 'This dispute is already closed.'];
            return back()->withNotify($notify);
        }

        DisputeService::reject($dispute, (int) auth()->guard('admin')->id(), $request->admin_note);

        $notify[] = ['success', 'Dispute rejected and closed.'];
        return back()->withNotify($notify);
    }
}
