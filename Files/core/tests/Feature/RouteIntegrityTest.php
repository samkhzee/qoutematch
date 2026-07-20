<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteIntegrityTest extends TestCase
{
    /**
     * Every route that references a controller method must point to a method
     * that actually exists on that controller class.
     */
    public function test_all_controller_routes_resolve_to_existing_methods(): void
    {
        $missing = [];

        foreach (Route::getRoutes() as $route) {
            $action = $route->getAction();

            // Skip closure / redirect / view routes
            if (! isset($action['controller'])) {
                continue;
            }

            if (! str_contains($action['controller'], '@')) {
                continue;
            }

            [$class, $method] = explode('@', $action['controller']);

            if (! class_exists($class)) {
                $missing[] = "{$route->getName()} → {$class}@{$method} (class missing)";
                continue;
            }

            if (! method_exists($class, $method)) {
                $missing[] = "{$route->getName()} → {$class}@{$method} (method missing)";
            }
        }

        $this->assertEmpty(
            $missing,
            "Routes pointing to missing controller methods:\n" . implode("\n", $missing)
        );
    }

    /**
     * A static GET route registered after a wildcard GET route on the same
     * prefix is unreachable.  For example:
     *
     *   GET bids/{id?}   ← registered first, matches everything
     *   GET bids/sort    ← registered later, never reached
     */
    public function test_no_static_get_route_is_shadowed_by_an_earlier_wildcard(): void
    {
        $getRoutes = collect(Route::getRoutes())
            ->filter(fn ($r) => in_array('GET', $r->methods()))
            ->values();

        $shadows = [];

        foreach ($getRoutes as $idx => $route) {
            $uri = $route->uri();

            // Only inspect static segments (no {param})
            if (preg_match('/\{/', $uri)) {
                continue;
            }

            // Walk earlier routes looking for a wildcard that would swallow this URI
            for ($i = 0; $i < $idx; $i++) {
                $earlier = $getRoutes[$i];
                $earlierUri = $earlier->uri();

                // Quick check: must share a prefix and contain a wildcard segment
                if (! preg_match('/\{/', $earlierUri)) {
                    continue;
                }

                // Build a regex from the earlier URI
                $pattern = preg_replace('/\{[^}]+\?\}/', '[^/]*', $earlierUri);
                $pattern = preg_replace('/\{[^}]+\}/', '[^/]+', $pattern);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri)) {
                    $shadows[] = sprintf(
                        '%s  GET %s  is shadowed by earlier  GET %s (%s)',
                        $route->getName() ?? '(unnamed)',
                        $uri,
                        $earlierUri,
                        $earlier->getName() ?? '(unnamed)',
                    );
                }
            }
        }

        $this->assertEmpty(
            $shadows,
            "Static GET routes shadowed by earlier wildcards:\n" . implode("\n", $shadows)
        );
    }
}
