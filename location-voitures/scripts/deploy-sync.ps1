param()

$ErrorActionPreference = 'Stop'

Write-Host "== Deploy Sync Start ==" -ForegroundColor Cyan

if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host ".env created from .env.example"
}

Write-Host "Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

Write-Host "Installing Node dependencies..."
npm ci

Write-Host "Building frontend assets..."
npm run build

Write-Host "Running migrations and seeders..."
php artisan migrate --seed --force

Write-Host "Clearing Laravel caches..."
php artisan optimize:clear

Write-Host "Ensuring storage symlink..."
php artisan storage:link

Write-Host "== Deploy Sync Done ==" -ForegroundColor Green
Write-Host "Run server with: php artisan serve"

