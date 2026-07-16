<?php

/**
 * One-time script: convert `return view(...)` to InertiaBridge in controllers.
 * Run: php scripts/convert-controllers-to-inertia.php
 */

$base = dirname(__DIR__);
$paths = [
    $base . '/app/Http/Controllers',
    $base . '/app/Traits/SupportTicketManager.php',
];

function bridgeType(string $view): ?string
{
    if (str_contains($view, '$')) {
        return null; // dynamic views handled manually
    }

    if (str_starts_with($view, 'admin.')) {
        return 'admin';
    }

    if (str_starts_with($view, 'Template::buyer.auth.') || str_starts_with($view, 'Template::user.auth.')) {
        return 'auth';
    }

    if (str_starts_with($view, 'Template::buyer.')) {
        return 'buyer';
    }

    if (str_starts_with($view, 'Template::user.')) {
        return 'master';
    }

    if (str_starts_with($view, 'Template::')) {
        return 'frontend';
    }

    return 'bare';
}

function convertFile(string $file): int
{
    $content = file_get_contents($file);
    $original = $content;

    // Skip already converted Inertia renders (keep explicit Inertia::render)
    $content = preg_replace_callback(
        '/return\s+view\s*\(\s*([\'"])([^\'"]+)\1\s*,\s*(compact\s*\([^)]+\)|[^;]+)\s*\)\s*;/',
        function ($matches) {
            $view = $matches[2];
            $args = trim($matches[3]);

            // Skip if already using bridge or inertia
            $type = bridgeType($view);
            if (!$type) {
                return $matches[0];
            }

            return "return \\App\\Lib\\InertiaBridge::{$type}('{$view}', {$args});";
        },
        $content
    );

    // return view('x')->with(...)
    $content = preg_replace_callback(
        '/return\s+view\s*\(\s*([\'"])([^\'"]+)\1\s*\)\s*->with\s*\(/',
        function ($matches) {
            $view = $matches[2];
            $type = bridgeType($view);
            if (!$type) {
                return $matches[0];
            }

            return "return \\App\\Lib\\InertiaBridge::{$type}('{$view}', " . '';
        },
        $content
    );

    if ($content !== $original) {
        file_put_contents($file, $content);
        return 1;
    }

    return 0;
}

$converted = 0;
foreach ($paths as $path) {
    if (is_file($path)) {
        $converted += convertFile($path);
        continue;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }
        $converted += convertFile($file->getPathname());
    }
}

echo "Converted files: {$converted}\n";
