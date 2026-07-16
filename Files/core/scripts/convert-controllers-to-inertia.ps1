$base = Split-Path -Parent $PSScriptRoot
$pattern1 = 'return\s+view\s*\(\s*([''"])([^''"]+)\1\s*,\s*(compact\s*\([^)]+\))\s*\)\s*;'
$pattern2 = 'return\s+view\s*\(\s*([''"])([^''"]+)\1\s*\)\s*->with\s*\(\s*(\[[\s\S]*?\])\s*\)\s*;'
$pattern3 = 'return\s+view\s*\(\s*"Template::\$this->userType"\s*\.\s*''([^'']+)''\s*,\s*(compact\s*\([^)]+\))\s*\)\s*;'

function Get-BridgeType([string]$view) {
    if ($view -match '\$|\{') { return $null }
    if ($view.StartsWith('admin.')) { return 'admin' }
    if ($view.StartsWith('Template::buyer.auth.') -or $view.StartsWith('Template::user.auth.')) { return 'auth' }
    if ($view.StartsWith('Template::buyer.')) { return 'buyer' }
    if ($view.StartsWith('Template::user.')) { return 'master' }
    if ($view.StartsWith('Template::')) { return 'frontend' }
    return 'bare'
}

function Convert-File([string]$file) {
    $content = Get-Content -Path $file -Raw
    $original = $content

    $content = [regex]::Replace($content, $pattern1, {
        param($m)
        $view = $m.Groups[2].Value
        $args = $m.Groups[3].Value
        $type = Get-BridgeType $view
        if (-not $type) { return $m.Value }
        "return \App\Lib\InertiaBridge::$type('$view', $args);"
    })

    $content = [regex]::Replace($content, $pattern2, {
        param($m)
        $view = $m.Groups[2].Value
        $args = $m.Groups[3].Value
        $type = Get-BridgeType $view
        if (-not $type) { return $m.Value }
        "return \App\Lib\InertiaBridge::$type('$view', $args);"
    })

    $content = [regex]::Replace($content, $pattern3, {
        param($m)
        $suffix = $m.Groups[1].Value
        $args = $m.Groups[2].Value
        "return \App\Lib\InertiaBridge::forUserType(`$this->userType, '$suffix', $args);"
    })

    if ($content -ne $original) {
        Set-Content -Path $file -Value $content -NoNewline
        return $true
    }
    return $false
}

$files = Get-ChildItem -Path (Join-Path $base 'app\Http\Controllers') -Filter '*.php' -Recurse
$files += Get-Item (Join-Path $base 'app\Traits\SupportTicketManager.php')

$count = 0
foreach ($f in $files) {
    if (Convert-File $f.FullName) {
        Write-Host "Updated: $($f.FullName)"
        $count++
    }
}
Write-Host "Done. Updated $count files."
