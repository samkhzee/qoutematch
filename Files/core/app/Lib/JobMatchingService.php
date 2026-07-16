<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\Job;
use App\Models\SeoLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class JobMatchingService
{
    /**
     * Public postcode outcode match check (Module 39).
     */
    public static function hasPostcodeOutcodeMatch(Job $job, User $provider): bool
    {
        if (!self::jobMatchesProvider($job, $provider)) {
            return false;
        }

        return self::hasOutcodeMatch(
            self::providerAreaTerms($provider),
            self::jobLocationTerms($job)
        );
    }

    /**
     * Browse page: match provider subcategories only (service areas checked when placing a bid).
     */
    public static function applyProviderListingMatching(Builder $query, ?User $provider): Builder
    {
        if (!$provider || !$provider->provider_approved) {
            return $query;
        }

        $subcategoryIds = array_filter((array) ($provider->subcategory_ids ?? []));

        if (!empty($subcategoryIds)) {
            $query->whereIn('subcategory_id', $subcategoryIds);
        }

        return $query;
    }

    /**
     * Restrict job listings to those matching the provider's categories and service areas.
     */
    public static function applyProviderMatching(Builder $query, ?User $provider): Builder
    {
        $query = self::applyProviderListingMatching($query, $provider);

        if (!$provider || !$provider->provider_approved) {
            return $query;
        }

        $areas = self::providerAreaTerms($provider);
        if (empty($areas)) {
            return $query;
        }

        return $query->where(function (Builder $inner) use ($areas) {
            foreach ($areas as $area) {
                $inner->orWhere('request_data', 'like', '%' . addcslashes($area, '%_\\') . '%');
            }
        });
    }

    /**
     * Provider eligibility to interact with marketplace jobs (approved + active).
     */
    public static function jobMatchesProvider(Job $job, User $provider): bool
    {
        if (!$provider->provider_approved || $provider->status != Status::USER_ACTIVE) {
            return false;
        }

        return true;
    }

    /**
     * Strict match (subcategory + service area / location) for recommendations.
     */
    public static function jobStronglyMatchesProvider(Job $job, User $provider): bool
    {
        if (!self::jobMatchesProvider($job, $provider)) {
            return false;
        }

        $subcategoryIds = array_filter((array) ($provider->subcategory_ids ?? []));
        if (!empty($subcategoryIds) && !in_array((int) $job->subcategory_id, array_map('intval', $subcategoryIds), true)) {
            return false;
        }

        $providerAreas = self::providerAreaTerms($provider);
        if (empty($providerAreas)) {
            return true;
        }

        $jobTerms = self::jobLocationTerms($job);

        foreach ($providerAreas as $area) {
            foreach ($jobTerms as $term) {
                if (self::termsMatch($area, $term)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Match score 0–100 for sorting recommendations (Module 23).
     */
    public static function matchScore(Job $job, User $provider): int
    {
        if (!self::jobMatchesProvider($job, $provider)) {
            return 0;
        }

        $score = 40;

        $subcategoryIds = array_filter((array) ($provider->subcategory_ids ?? []));
        if (!empty($subcategoryIds) && in_array((int) $job->subcategory_id, array_map('intval', $subcategoryIds), true)) {
            $score += 30;
        }

        if (self::jobStronglyMatchesProvider($job, $provider)) {
            $score += 30;
        } else {
            $providerAreas = self::providerAreaTerms($provider);
            $jobTerms = self::jobLocationTerms($job);

            if (self::hasOutcodeMatch($providerAreas, $jobTerms)) {
                $score += 15;
            }
        }

        return min(100, $score);
    }

    public static function jobLocationTerms(Job $job): array
    {
        $terms = [];
        $requestData = $job->request_data ?? [];

        foreach ($requestData as $item) {
            $name = strtolower((string) ($item['name'] ?? ''));
            $value = trim((string) ($item['value'] ?? ''));

            if ($value === '') {
                continue;
            }

            if (preg_match('/postcode|post code|zip|location|city|destination|origin|area/i', $name)) {
                $terms = array_merge($terms, self::expandLocationTerm($value));
            }
        }

        return array_values(array_unique(array_filter($terms)));
    }

    protected static function providerAreaTerms(User $provider): array
    {
        $terms = self::normalizeAreas($provider->service_areas);

        // If provider stores raw postcodes (e.g. "SW1A 1AA" or "SW1A"), expand with the outcode.
        $expanded = [];
        foreach ($terms as $term) {
            $expanded[] = $term;
            if ($outcode = self::extractPostcodeOutcode($term)) {
                $expanded[] = $outcode;
            }
        }

        $terms = array_values(array_unique(array_filter($expanded)));

        foreach (SeoLocation::active()->get(['name', 'slug', 'region']) as $location) {
            foreach ($terms as $term) {
                if (self::termsMatch($term, $location->name) || self::termsMatch($term, $location->slug)) {
                    $terms[] = $location->name;
                    $terms[] = $location->slug;
                    if ($location->region) {
                        $terms[] = $location->region;
                    }
                }
            }
        }

        return array_values(array_unique(array_filter($terms)));
    }

    protected static function expandLocationTerm(string $value): array
    {
        $terms = [trim($value)];
        $upper = strtoupper(trim($value));

        if ($outcode = self::extractPostcodeOutcode($value)) {
            $terms[] = $outcode;
        }

        $slug = Str::slug($value);
        if ($slug) {
            $terms[] = $slug;
        }

        $location = SeoLocation::active()
            ->where(function ($query) use ($value, $slug) {
                $query->where('name', 'like', '%' . $value . '%')
                    ->orWhere('slug', $slug);
            })
            ->first();

        if ($location) {
            $terms[] = $location->name;
            $terms[] = $location->slug;
            if ($location->region) {
                $terms[] = $location->region;
            }
        }

        return array_values(array_unique(array_filter($terms)));
    }

    /**
     * Extract the postcode outcode (e.g. "SW1A" from "SW1A 1AA").
     */
    protected static function extractPostcodeOutcode(string $value): ?string
    {
        $upper = strtoupper(preg_replace('/\s+/', ' ', trim($value)));

        // Common: full postcode with a space (or extra spaces): SW1A 1AA
        if (preg_match('/\b([A-Z]{1,2}\d[A-Z\d]?)\s*\d[A-Z]{2}\b/', $upper, $matches)) {
            return $matches[1];
        }

        // Outcode only: SW1A
        if (preg_match('/\b([A-Z]{1,2}\d[A-Z\d]?)\b/', $upper, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Detect whether provider/job terms share a postcode outcode match.
     */
    protected static function hasOutcodeMatch(array $providerAreas, array $jobTerms): bool
    {
        foreach ($providerAreas as $area) {
            foreach ($jobTerms as $term) {
                $outA = self::extractPostcodeOutcode((string) $area);
                $outB = self::extractPostcodeOutcode((string) $term);

                if ($outA && $outB && strtoupper($outA) === strtoupper($outB)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected static function termsMatch(string $a, string $b): bool
    {
        $a = strtolower(trim($a));
        $b = strtolower(trim($b));

        if ($a === '' || $b === '') {
            return false;
        }

        return $a === $b || str_contains($a, $b) || str_contains($b, $a);
    }

    protected static function normalizeAreas(?string $serviceAreas): array
    {
        if (!$serviceAreas) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($part) => trim($part),
            preg_split('/[,;\n\r]+/', $serviceAreas) ?: []
        )));
    }
}
