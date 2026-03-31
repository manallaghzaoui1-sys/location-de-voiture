<?php

namespace App\Providers;

use App\Services\CarSnapshotService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ((bool) env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        if (! app()->runningInConsole()) {
            try {
                app(CarSnapshotService::class)->syncToDatabaseIfChanged();
            } catch (\Throwable $exception) {
                report($exception);
            }
        }
    }
}
