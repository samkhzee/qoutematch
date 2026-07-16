<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\AdminResource;
use App\Models\Bid;
use App\Models\Conversation;
use Inertia\Inertia;

class ManageBidController extends Controller
{

    public function jobBids($id = 0)
    {
        $pageTitle = $id ? 'Quotes for Request' : 'All Bids';
        $bids = Bid::with(['job', 'user', 'project', 'buyer'])
            ->when($id, function ($query) use ($id) {
                $query->where('job_id', $id);
            })->searchable(['job:title', 'user:username', 'buyer:username'])->filter(['status'])->dateFilter()->orderByDesc('id')->paginate(getPaginate());

        return Inertia::render('Admin/Bids/Index', [
            'pageTitle' => $pageTitle,
            'jobId' => (int) $id,
            'bids' => AdminResource::bids($bids, (int) $id),
        ]);
    }

    public function detail($id)
    {
        $bid = Bid::with(['job.category', 'job.subcategory', 'user', 'buyer', 'project'])->findOrFail($id);
        $pageTitle = 'Quote Detail — ' . ($bid->user->username ?? 'Provider');
        $quoteFields = \App\Lib\RequestFormService::displayValues($bid->quote_data, 'admin.download.attachment');
        $requestFields = \App\Lib\RequestFormService::displayValues($bid->job?->request_data, 'admin.download.attachment');

        return Inertia::render('Admin/Bids/Detail', [
            'pageTitle' => $pageTitle,
            'bid' => AdminResource::bidDetail($bid, $quoteFields, $requestFields),
        ]);
    }

    public function bidDelete($id)
    {
        $bid = Bid::with(['job', 'project'])->findOrFail($id);

        if ((int) $bid->status === Status::BID_ACCEPTED) {
            $notify[] = ['error', 'Cannot delete a hired quote. Reject it from the buyer dashboard instead.'];
            return back()->withNotify($notify);
        }

        if ($bid->project && (int) $bid->project->status === Status::PROJECT_RUNNING) {
            $notify[] = ['error', 'Cannot delete a quote linked to an active project.'];
            return back()->withNotify($notify);
        }

        Conversation::where('bid_id', $bid->id)->each(function (Conversation $conversation) {
            $conversation->messages()->delete();
            $conversation->delete();
        });

        $bid->delete();

        $notify[] = ['success', 'Quote deleted successfully.'];
        return back()->withNotify($notify);
    }
}
