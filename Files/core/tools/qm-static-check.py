#!/usr/bin/env python3
"""QuoteMatch static route checker.

Parses route files for controller-backed routes and verifies the
referenced controller methods exist on disk.
"""

import os
import re
import sys
from pathlib import Path

# Resolve the project root (Files/core) relative to this script's location.
SCRIPT_DIR = Path(__file__).resolve().parent          # tools/
CORE_DIR = SCRIPT_DIR.parent                          # Files/core

ROUTES_DIR = CORE_DIR / "routes"
CONTROLLERS_DIR = CORE_DIR / "app" / "Http" / "Controllers"


def find_route_files():
    """Return all .php files under the routes/ directory."""
    if not ROUTES_DIR.is_dir():
        return []
    return sorted(ROUTES_DIR.glob("*.php"))


def parse_controller_routes(route_file: Path):
    """Yield (controller_class, method, route_name) tuples from a route file."""
    content = route_file.read_text(encoding="utf-8", errors="replace")

    # Match Route::controller('FooController') groups
    controller_block = re.compile(
        r"Route::controller\(\s*['\"]([^'\"]+)['\"]\s*\)", re.MULTILINE
    )
    # Match ->name('something') on the same or nearby line
    route_method = re.compile(
        r"Route::(get|post|put|patch|delete|any|match)\s*\(\s*['\"][^'\"]*['\"]\s*,\s*['\"](\w+)['\"]\s*\)"
    )
    # Match chained controller actions inside a controller group:
    # Route::get('path', 'method')->name(...)
    chained_method = re.compile(
        r"Route::(get|post|put|patch|delete|any)\s*\(\s*['\"][^'\"]*['\"]\s*,\s*['\"](\w+)['\"]\s*\)"
    )

    # Approach: find controller blocks and extract methods within them
    results = []

    # Find all controller group usages
    blocks = list(controller_block.finditer(content))

    for i, block_match in enumerate(blocks):
        controller_short = block_match.group(1)
        start = block_match.end()
        # End is either next controller block or end of file
        end = blocks[i + 1].start() if i + 1 < len(blocks) else len(content)
        segment = content[start:end]

        for m in chained_method.finditer(segment):
            method_name = m.group(2)
            # Try to extract route name
            rest = segment[m.end(): m.end() + 200]
            name_match = re.search(r"->name\(\s*['\"]([^'\"]+)['\"]\s*\)", rest)
            route_name = name_match.group(1) if name_match else "(unnamed)"
            results.append((controller_short, method_name, route_name))

    # Also match standalone Route::verb('path', [Controller::class, 'method'])
    standalone = re.compile(
        r"Route::(get|post|put|patch|delete|any)\s*\(\s*['\"][^'\"]*['\"]\s*,\s*\[([^]]+)\]\s*\)"
    )
    for m in standalone.finditer(content):
        parts = m.group(2).split(",")
        if len(parts) == 2:
            ctrl = parts[0].strip().strip("'\"").replace("::class", "")
            method = parts[1].strip().strip("'\"")
            rest = content[m.end(): m.end() + 200]
            name_match = re.search(r"->name\(\s*['\"]([^'\"]+)['\"]\s*\)", rest)
            route_name = name_match.group(1) if name_match else "(unnamed)"
            results.append((ctrl, method, route_name))

    return results


def resolve_controller_path(short_name: str):
    """Try to find the controller PHP file on disk."""
    # Handle namespace separators
    relative = short_name.replace("\\", os.sep) + ".php"
    candidate = CONTROLLERS_DIR / relative
    if candidate.is_file():
        return candidate
    # Try without namespace
    simple = CONTROLLERS_DIR / (short_name + ".php")
    if simple.is_file():
        return simple
    # Search recursively
    for f in CONTROLLERS_DIR.rglob(short_name + ".php"):
        return f
    return None


def controller_has_method(controller_path: Path, method: str):
    """Check whether a PHP controller file defines a given method."""
    content = controller_path.read_text(encoding="utf-8", errors="replace")
    pattern = re.compile(r"\bfunction\s+" + re.escape(method) + r"\s*\(")
    return bool(pattern.search(content))


def main():
    # Validate project directory
    if not CORE_DIR.is_dir():
        print(f"ERROR: Project directory does not exist: {CORE_DIR}", file=sys.stderr)
        sys.exit(1)

    route_files = find_route_files()
    if not route_files:
        print(f"ERROR: No route files found in {ROUTES_DIR}", file=sys.stderr)
        sys.exit(1)

    print(f"Project root: {CORE_DIR}")
    print(f"Route files:  {[f.name for f in route_files]}")

    all_routes = []
    for rf in route_files:
        routes = parse_controller_routes(rf)
        all_routes.extend([(rf.name, *r) for r in routes])

    print(f"Parsed {len(all_routes)} controller-backed routes")

    if len(all_routes) == 0:
        print("ERROR: Parsed zero controller-backed routes — check the parser.", file=sys.stderr)
        sys.exit(1)

    issues = []
    for route_file_name, controller, method, route_name in all_routes:
        ctrl_path = resolve_controller_path(controller)
        if ctrl_path is None:
            issues.append(f"  [{route_file_name}] {route_name}: controller '{controller}' not found on disk")
        elif not controller_has_method(ctrl_path, method):
            issues.append(f"  [{route_file_name}] {route_name}: method '{method}' missing in {ctrl_path.name}")

    if issues:
        print(f"\n{len(issues)} issue(s) found:")
        for issue in issues:
            print(issue)
        sys.exit(1)
    else:
        print("0 issues")
        sys.exit(0)


if __name__ == "__main__":
    main()
