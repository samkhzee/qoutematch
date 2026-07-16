<?php

namespace App\Lib;

use App\Models\Form;

class AuthResource
{
    public static function forgotPassword(string $role, ?string $captchaHtml = null): array
    {
        $prefix = $role === 'buyer' ? 'buyer' : 'user';

        return [
            'submitUrl' => route("{$prefix}.password.email"),
            'loginUrl' => route("{$prefix}.login"),
            'captchaHtml' => ($captchaHtml && $captchaHtml !== '0') ? $captchaHtml : null,
        ];
    }

    public static function resetCodeVerify(string $email, string $role): array
    {
        $prefix = $role === 'buyer' ? 'buyer' : 'user';

        return [
            'email' => $email,
            'maskedEmail' => showEmailAddress($email),
            'submitUrl' => route("{$prefix}.password.verify.code"),
            'resendUrl' => route("{$prefix}.password.request"),
            'devResetCode' => ($code = session('pass_res_dev_code')) !== null ? (string) $code : null,
            'localMailInboxUrl' => app()->environment('local') ? 'http://127.0.0.1:8025' : null,
        ];
    }

    public static function resetPassword(string $email, string $token, string $role, bool $securePassword): array
    {
        $prefix = $role === 'buyer' ? 'buyer' : 'user';

        return [
            'email' => $email,
            'token' => (string) $token,
            'submitUrl' => route("{$prefix}.password.update"),
            'loginUrl' => route("{$prefix}.login"),
            'securePassword' => $securePassword,
        ];
    }

    public static function authorization($user, string $type, string $pageTitle, string $role): array
    {
        $prefix = $role === 'buyer' ? 'buyer' : 'user';
        $countdown = 0;

        if ($user->ver_code_send_at && !in_array($type, ['2fa', 'ban'], true)) {
            $countdown = max(0, $user->ver_code_send_at->copy()->addMinutes(2)->timestamp - time());
        }

        $banned = getContent('banned.content', true);

        return [
            'type' => $type,
            'pageTitle' => $pageTitle,
            'maskedEmail' => showEmailAddress($user->email),
            'maskedMobile' => showMobileNumber($user->mobile),
            'banReason' => $user->ban_reason,
            'banImage' => getImage('assets/images/frontend/banned/' . (@$banned->data_values->image ?: ''), '360x370'),
            'banHeading' => __(@$banned->data_values->heading ?: 'You are banned'),
            'countdownSeconds' => $countdown,
            'submitUrl' => match ($type) {
                'email' => route("{$prefix}.verify.email"),
                'sms' => route("{$prefix}.verify.mobile"),
                '2fa' => route("{$prefix}.2fa.verify"),
                default => null,
            },
            'resendUrl' => in_array($type, ['email', 'sms'], true)
                ? route("{$prefix}.send.verify.code", $type)
                : null,
            'logoutUrl' => route("{$prefix}.logout"),
            'homeUrl' => route('home'),
        ];
    }
}
