<?php



namespace App\Http\Controllers\Admin;



use App\Constants\Status;

use App\Http\Controllers\Controller;

use App\Lib\AdminResource;

use App\Lib\StructuredReviewService;

use App\Models\Review;

use Illuminate\Http\Request;

use Inertia\Inertia;



class ReviewModerationController extends Controller

{

    public function index()

    {

        $pageTitle = 'Review Moderation';

        $status = request()->get('status', 'pending');



        $reviews = Review::query()

            ->with(['user', 'buyer', 'project.job'])

            ->when($status === 'pending', fn ($query) => $query->where('status', Status::REVIEW_PENDING))

            ->when($status === 'approved', fn ($query) => $query->where('status', Status::REVIEW_APPROVED))

            ->when($status === 'hidden', fn ($query) => $query->where('status', Status::REVIEW_HIDDEN))

            ->when(request()->filled('user_id'), fn ($query) => $query->where('user_id', request()->integer('user_id')))

            ->latest('id')

            ->paginate(getPaginate());



        return Inertia::render('Admin/Reviews/Index', [

            'pageTitle' => $pageTitle,

            'reviews' => AdminResource::reviews($reviews, $status),

        ]);

    }



    public function detail($id)

    {

        $pageTitle = 'Review Detail';

        $review = Review::with(['user', 'buyer', 'project.job'])->findOrFail($id);



        return Inertia::render('Admin/Reviews/Detail', [

            'pageTitle' => $pageTitle,

            'review' => AdminResource::reviewDetail($review),

        ]);

    }



    public function approve($id)

    {

        $review = Review::with('user')->findOrFail($id);



        if ((int) $review->status === Status::REVIEW_APPROVED) {

            $notify[] = ['error', 'This review is already approved.'];

            return back()->withNotify($notify);

        }



        StructuredReviewService::approve($review);



        $notify[] = ['success', 'Review approved and published on the provider profile.'];

        return back()->withNotify($notify);

    }



    public function hide(Request $request, $id)

    {

        $request->validate([

            'admin_note' => 'nullable|string|max:1000',

        ]);



        $review = Review::with('user')->findOrFail($id);



        if ((int) $review->status === Status::REVIEW_HIDDEN) {

            $notify[] = ['error', 'This review is already hidden.'];

            return back()->withNotify($notify);

        }



        StructuredReviewService::hide($review, $request->admin_note);



        $notify[] = ['success', 'Review hidden from public profiles.'];

        return back()->withNotify($notify);

    }

}

