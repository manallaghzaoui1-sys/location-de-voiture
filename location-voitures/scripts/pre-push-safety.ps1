param()

$ErrorActionPreference = 'Stop'

Write-Host "Running pre-push safety checks..." -ForegroundColor Cyan

$trackedEnv = git ls-files .env
if ($trackedEnv) {
    Write-Error ".env is tracked by git. Remove it from tracking before push."
}

$trackedSensitive = git ls-files | Select-String -Pattern '\.sql$|server\.log$|server-error\.log$'
if ($trackedSensitive) {
    Write-Error "Sensitive/generated files are tracked:`n$($trackedSensitive -join "`n")"
}

php artisan test

Write-Host "Pre-push checks passed. Safe to push." -ForegroundColor Green
