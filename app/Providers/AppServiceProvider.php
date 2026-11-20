<?php

namespace App\Providers;

use App\Services\Cpanel\CpanelTransport;
use App\Services\Cpanel\HttpTransport;
use App\Services\Cpanel\UapiTransport;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // cPanel transport for the mailbox portal: local `uapi` (default) or HTTPS with a token.
        $this->app->bind(CpanelTransport::class, function () {
            return config('services.cpanel.transport') === 'http'
                ? new HttpTransport
                : new UapiTransport((string) config('services.cpanel.uapi_bin', 'uapi'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
