<?php



namespace App\Http\Controllers\Admin;



use App\Constants\Status;

use App\Http\Controllers\Controller;

use App\Lib\AdminResource;

use App\Models\ProviderVerification;

use Illuminate\Http\Request;

use Inertia\Inertia;



class ProviderVerificationController extends Controller

{

    public function index()

    {

        $pageTitle = 'Provider Verifications';

        $status = request()->get('status', 'pending');



        $verifications = ProviderVerification::query()

            ->with(['user'])

            ->when($status === 'pending', fn ($query) => $query->where('status', Status::VERIFICATION_PENDING))

            ->when($status === 'approved', fn ($query) => $query->where('status', Status::VERIFICATION_APPROVED))

            ->when($status === 'rejected', fn ($query) => $query->where('status', Status::VERIFICATION_REJECTED))

            ->when(request()->filled('user_id'), fn ($query) => $query->where('user_id', request()->integer('user_id')))

            ->latest('id')

            ->paginate(getPaginate());



        return Inertia::render('Admin/Verifications/Index', [

            'pageTitle' => $pageTitle,

            'verifications' => AdminResource::verifications($verifications, $status),

        ]);

    }



    public function detail($id)

    {

        $pageTitle = 'Verification Detail';

        $verification = ProviderVerification::with(['user'])->findOrFail($id);



        return Inertia::render('Admin/Verifications/Detail', [

            'pageTitle' => $pageTitle,

            'verification' => AdminResource::verificationDetail($verification),

        ]);

    }



    public function approve($id)

    {

        $verification = ProviderVerification::with('user')->findOrFail($id);



        if ((int) $verification->status !== Status::VERIFICATION_PENDING) {

            $notify[] = ['error', 'This verification has already been reviewed.'];

            return back()->withNotify($notify);

        }



        $verification->status = Status::VERIFICATION_APPROVED;

        $verification->reviewed_at = now();

        $verification->reviewed_by = auth()->guard('admin')->id();

        $verification->admin_note = null;

        $verification->save();



        notify($verification->user, 'PROVIDER_VERIFICATION_APPROVED', [

            'type' => $verification->typeLabel(),

            'provider' => $verification->user->fullname,

        ]);



        $notify[] = ['success', 'Verification approved successfully.'];

        return back()->withNotify($notify);

    }



    public function reject(Request $request, $id)

    {

        $request->validate([

            'admin_note' => 'required|string|min:5|max:1000',

        ]);



        $verification = ProviderVerification::with('user')->findOrFail($id);



        if ((int) $verification->status !== Status::VERIFICATION_PENDING) {

            $notify[] = ['error', 'This verification has already been reviewed.'];

            return back()->withNotify($notify);

        }



        $verification->status = Status::VERIFICATION_REJECTED;

        $verification->reviewed_at = now();

        $verification->reviewed_by = auth()->guard('admin')->id();

        $verification->admin_note = $request->admin_note;

        $verification->save();



        notify($verification->user, 'PROVIDER_VERIFICATION_REJECTED', [

            'type' => $verification->typeLabel(),

            'provider' => $verification->user->fullname,

            'note' => $request->admin_note,

        ]);



        $notify[] = ['success', 'Verification rejected. The provider can resubmit.'];

        return back()->withNotify($notify);

    }

}

