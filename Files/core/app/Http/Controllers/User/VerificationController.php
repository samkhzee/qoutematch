<?php

namespace App\Http\Controllers\User;

use App\Constants\ProviderVerificationType;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\VerificationBadgeService;
use App\Models\AdminNotification;
use App\Models\ProviderVerification;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VerificationController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('providerVerifications');

        return Inertia::render('User/Profile/Verification', [
            'pageTitle' => 'Verification Badges',
            'identity' => [
                'verified' => VerificationBadgeService::isIdentityVerified($user),
                'statusLabel' => match ((int) $user->kv) {
                    Status::KYC_VERIFIED => __('Verified'),
                    Status::KYC_PENDING => __('Pending review'),
                    default => __('Not submitted'),
                },
                'kycFormUrl' => route('user.kyc.form'),
                'kycDataUrl' => route('user.kyc.data'),
            ],
            'providerApproved' => VerificationBadgeService::isProviderApproved($user),
            'documents' => VerificationBadgeService::providerFormRows($user),
            'badges' => VerificationBadgeService::badgesForUser($user),
        ]);
    }

    public function store(Request $request, string $type)
    {
        if (!in_array($type, ProviderVerificationType::all(), true)) {
            abort(404);
        }

        $user = auth()->user();
        $existing = ProviderVerification::query()
            ->where('user_id', $user->id)
            ->where('type', $type)
            ->first();

        if ($existing && (int) $existing->status !== Status::VERIFICATION_REJECTED) {
            $notify[] = ['error', 'This verification is already submitted or approved.'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'document' => ['required', 'file', 'max:5120', new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf'])],
            'reference_number' => 'nullable|string|max:120',
            'expires_at' => 'nullable|date|after:today',
        ]);

        try {
            if ($existing?->document) {
                fileManager()->removeFile(getFilePath('verify') . '/' . $existing->document);
            }

            $document = fileUploader($request->file('document'), getFilePath('verify'));
        } catch (\Exception $exception) {
            $notify[] = ['error', 'Could not upload your document. Please try again.'];
            return back()->withNotify($notify);
        }

        $record = $existing ?? new ProviderVerification();
        $record->user_id = $user->id;
        $record->type = $type;
        $record->document = $document;
        $record->reference_number = $request->reference_number;
        $record->expires_at = $request->expires_at;
        $record->status = Status::VERIFICATION_PENDING;
        $record->admin_note = null;
        $record->reviewed_at = null;
        $record->reviewed_by = null;
        $record->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = $user->fullname . ' submitted ' . ProviderVerificationType::label($type) . ' for review';
        $adminNotification->click_url = urlPath('admin.provider.verifications.detail', $record->id);
        $adminNotification->save();

        $notify[] = ['success', ProviderVerificationType::label($type) . ' submitted for admin review.'];
        return back()->withNotify($notify);
    }
}
