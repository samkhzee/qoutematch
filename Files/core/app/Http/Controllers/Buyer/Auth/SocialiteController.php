<?php

namespace App\Http\Controllers\Buyer\Auth;

use App\Http\Controllers\Controller;
use App\Lib\BuyerSocialLogin;

class SocialiteController extends Controller
{
    public function socialLogin($provider)
    {
        $socialLogin = new BuyerSocialLogin($provider);
        return $socialLogin->redirectDriver();
    }

    public function callback($provider)
    {
        $socialLogin = new BuyerSocialLogin($provider);
        try {
            return $socialLogin->login();
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
            return to_route('home')->withNotify($notify);
        }
    }
}
