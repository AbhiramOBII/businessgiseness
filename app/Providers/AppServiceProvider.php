<?php

namespace App\Providers;

use App\Mail\Transport\ZeptoMailTransport;
use Illuminate\Support\Facades\Mail;
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
        // In production, always generate https links (canonical, sitemap, OG tags),
        // even if the host's proxy hands requests to PHP over plain http.
        if ($this->app->isProduction() && str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // MAIL_MAILER=zeptomail sends mail through the ZeptoMail API
        Mail::extend('zeptomail', fn (array $config) => new ZeptoMailTransport(
            (string) ($config['key'] ?? ''),
            $config['host'] ?? 'api.zeptomail.in',
        ));
    }
}
