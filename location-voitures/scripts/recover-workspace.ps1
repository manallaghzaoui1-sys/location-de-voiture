param()

$ErrorActionPreference = 'Stop'

Write-Host "Recovering workspace..." -ForegroundColor Cyan

if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host ".env created from .env.example"
}

php artisan optimize:clear
php artisan migrate --force
php artisan storage:link

Write-Host "Workspace recovery completed." -ForegroundColor Green
Write-Host "Start app with: php artisan serve"
