<?php

namespace App\Lib;

use Inertia\Inertia;
use Inertia\Response;

class InertiaBridge
{
    /**
     * Render a Blade view inside the React/Inertia app shell.
     * Used for pages not yet fully rewritten as JSX while keeping 100% Inertia routing.
     */
    public static function page(string $layout, string $blade, array $data = []): Response
    {
        $data['inertiaBridge'] = true;
        $html = view($blade, $data)->render();

        return Inertia::render('Bridge/Page', [
            'layout' => $layout,
            'html' => $html,
            'pageTitle' => $data['pageTitle'] ?? null,
            'seo' => $data['seo'] ?? null,
        ]);
    }

    public static function frontend(string $blade, array $data = []): Response
    {
        return self::page('frontend', $blade, $data);
    }

    public static function master(string $blade, array $data = []): Response
    {
        return self::page('master', $blade, $data);
    }

    public static function buyer(string $blade, array $data = []): Response
    {
        return self::page('buyer', $blade, $data);
    }

    public static function admin(string $blade, array $data = [])
    {
        // Admin panel uses its own Blade layout (CSS/JS stacks). Do not wrap in Inertia.
        return view($blade, $data);
    }

    public static function auth(string $blade, array $data = []): Response
    {
        return self::page('auth', $blade, $data);
    }

    public static function bare(string $blade, array $data = []): Response
    {
        $data['inertiaBridge'] = true;
        $html = view($blade, $data)->render();

        return Inertia::render('Bridge/Page', [
            'layout' => 'bare',
            'html' => $html,
            'pageTitle' => $data['pageTitle'] ?? null,
        ]);
    }

    /** Support tickets shared between freelancer (user) and buyer portals. */
    public static function forUserType(string $userType, string $suffix, array $data = []): Response
    {
        $blade = "Template::{$userType}{$suffix}";

        return match ($userType) {
            'user' => self::master($blade, $data),
            'buyer' => self::buyer($blade, $data),
            default => self::bare($blade, $data),
        };
    }
}
