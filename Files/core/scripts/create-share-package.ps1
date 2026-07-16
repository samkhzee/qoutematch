$ErrorActionPreference = "Stop"

$filesRoot   = "C:\laragon\www\codecanyon-MNzPySlM-olance-global-freelancing-marketplace\Files"
$coreRoot    = Join-Path $filesRoot "core"
$assetsRoot  = Join-Path $filesRoot "assets"
$buildRoot   = Join-Path $filesRoot "build"
$mysqlDump   = "C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe"
$dbName      = "olance"

$timestamp   = Get-Date -Format "yyyy-MM-dd_HHmm"
$packageName = "QuoteMatch-Project-With-Database_$timestamp"
$staging     = Join-Path $filesRoot "_export_staging"
$zipPath     = Join-Path $filesRoot "$packageName.zip"

if (Test-Path $staging) { Remove-Item $staging -Recurse -Force }
New-Item -ItemType Directory -Path (Join-Path $staging "database") -Force | Out-Null

Write-Host "1/4 Database export..."
$dumpFile = Join-Path $staging "database\olance.sql"
& $mysqlDump -u root --routines --triggers $dbName | Out-File -FilePath $dumpFile -Encoding utf8

Write-Host "2/4 Copying files (vendor/node_modules excluded for smaller zip)..."
$excludeDirs = @("node_modules", "vendor", ".git", ".cursor")

function Copy-Tree($Source, $Dest) {
    if (-not (Test-Path $Source)) { return }
    New-Item -ItemType Directory -Path $Dest -Force | Out-Null
    robocopy $Source $Dest /E /XD $excludeDirs "storage\logs" "storage\framework\cache" "storage\framework\sessions" "storage\framework\views" /XF "*.log" /NFL /NDL /NJH /NJS /nc /ns /np | Out-Null
}

Copy-Tree $coreRoot   (Join-Path $staging "core")
Copy-Tree $assetsRoot (Join-Path $staging "assets")
Copy-Tree $buildRoot  (Join-Path $staging "build")

@"
QuoteMatch - Project + Database
Generated: $(Get-Date)

CONTENTS
- core/       Laravel app
- assets/     CSS, images
- build/      Compiled frontend
- database/olance.sql

QUICK SETUP (Laragon)
1. Extract to C:\laragon\www\quotematch\Files\
2. Create DB: olance
3. Import: database\olance.sql (HeidiSQL / phpMyAdmin)
4. cd core
   composer install
   php artisan key:generate
   php artisan storage:link
5. .env: DB_DATABASE=olance, DB_USERNAME=root, APP_URL=http://127.0.0.1:8000
6. Open http://127.0.0.1:8000

Note: vendor excluded — run composer install after extract.
"@ | Set-Content (Join-Path $staging "INSTALL.txt") -Encoding UTF8

Write-Host "3/4 Creating zip..."
if (Test-Path $zipPath) { Remove-Item $zipPath -Force }
Compress-Archive -Path "$staging\*" -DestinationPath $zipPath -CompressionLevel Fastest

Write-Host "4/4 Cleanup..."
Remove-Item $staging -Recurse -Force

$mb = [math]::Round((Get-Item $zipPath).Length / 1MB, 2)
Write-Host "DONE: $zipPath ($mb MB)"
