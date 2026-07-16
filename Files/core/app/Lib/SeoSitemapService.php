<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Page;
use App\Models\SeoLocation;

class SeoSitemapService
{
    public static function generate(): string
    {
        $urls = array_merge(
            self::staticUrls(),
            self::categoryUrls(),
            self::locationUrls(),
            self::categoryLocationUrls(),
            self::cmsUrls(),
            self::blogUrls(),
            self::policyUrls(),
        );

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $entry) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . e($entry['loc']) . '</loc>' . PHP_EOL;
            if (!empty($entry['lastmod'])) {
                $xml .= '    <lastmod>' . e($entry['lastmod']) . '</lastmod>' . PHP_EOL;
            }
            if (!empty($entry['changefreq'])) {
                $xml .= '    <changefreq>' . e($entry['changefreq']) . '</changefreq>' . PHP_EOL;
            }
            if (!empty($entry['priority'])) {
                $xml .= '    <priority>' . e($entry['priority']) . '</priority>' . PHP_EOL;
            }
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    private static function staticUrls(): array
    {
        return [
            ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('categories'), 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => route('locations'), 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => route('freelance.jobs'), 'changefreq' => 'daily', 'priority' => '0.8'],
            ['loc' => route('all.freelancers'), 'changefreq' => 'daily', 'priority' => '0.8'],
            ['loc' => route('blogs'), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => route('contact'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('cookie.policy'), 'changefreq' => 'yearly', 'priority' => '0.4'],
        ];
    }

    private static function categoryUrls(): array
    {
        return Category::active()
            ->whereNotNull('slug')
            ->get()
            ->map(fn ($category) => [
                'loc' => route('categories.show', $category->slug),
                'lastmod' => optional($category->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ])
            ->all();
    }

    private static function locationUrls(): array
    {
        return SeoLocation::active()
            ->get()
            ->map(fn ($location) => [
                'loc' => route('locations.show', $location->slug),
                'lastmod' => optional($location->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ])
            ->all();
    }

    private static function categoryLocationUrls(): array
    {
        $categories = Category::active()
            ->where('is_featured', Status::YES)
            ->whereNotNull('slug')
            ->get();
        $locations = SeoLocation::active()->featured()->get();
        $urls = [];

        foreach ($categories as $category) {
            foreach ($locations as $location) {
                $urls[] = [
                    'loc' => SeoLocationService::categoryLocationUrl($category, $location),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            }
        }

        return $urls;
    }

    private static function cmsUrls(): array
    {
        return Page::where('is_default', Status::NO)
            ->where('tempname', activeTemplate())
            ->whereNotNull('slug')
            ->get()
            ->map(fn ($page) => [
                'loc' => url('/' . ltrim($page->slug, '/')),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ])
            ->all();
    }

    private static function blogUrls(): array
    {
        return Frontend::where('data_keys', 'blog.element')
            ->whereNotNull('slug')
            ->get()
            ->map(fn ($blog) => [
                'loc' => route('blog.details', $blog->slug),
                'lastmod' => optional($blog->updated_at)->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ])
            ->all();
    }

    private static function policyUrls(): array
    {
        return Frontend::where('data_keys', 'policy_pages.element')
            ->where('tempname', activeTemplateName())
            ->whereNotNull('slug')
            ->get()
            ->map(fn ($policy) => [
                'loc' => route('policy.pages', $policy->slug),
                'changefreq' => 'yearly',
                'priority' => '0.5',
            ])
            ->all();
    }
}
