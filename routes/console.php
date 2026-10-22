<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('mail:test {to : Email address to send the test to} {--mailer= : Mailer to use (defaults to MAIL_MAILER)}', function (string $to) {
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $this->error("\"{$to}\" is not a valid email address.");
        return 1;
    }

    $mailer = $this->option('mailer') ?: config('mail.default');
    if (!config("mail.mailers.{$mailer}")) {
        $this->error("Mailer \"{$mailer}\" is not configured in config/mail.php.");
        return 1;
    }

    $this->table(['Setting', 'Value'], array_filter([
        ['Mailer', $mailer],
        ['From', config('mail.from.name') . ' <' . config('mail.from.address') . '>'],
        ['To', $to],
        $mailer === 'zeptomail' ? ['ZeptoMail host', config('mail.mailers.zeptomail.host')] : null,
        $mailer === 'zeptomail' ? ['API key set', config('mail.mailers.zeptomail.key') ? 'yes' : 'NO — set ZEPTOMAIL_API_KEY'] : null,
    ]));

    if (str_ends_with((string) config('mail.from.address'), '@example.com')) {
        $this->warn('MAIL_FROM_ADDRESS is still the placeholder — set it to an address on your verified ZeptoMail domain.');
    }

    if ($mailer === 'zeptomail' && !config('mail.mailers.zeptomail.key')) {
        $this->error('ZEPTOMAIL_API_KEY is empty in .env (run "php artisan config:clear" after setting it).');
        return 1;
    }

    try {
        Mail::mailer($mailer)->html(
            '<div style="font-family:Arial,sans-serif;font-size:15px;color:#151828">'
            . '<p><b>Test email sent successfully.</b></p>'
            . '<p>This message was sent from ' . e(config('app.url')) . ' using the <code>' . e($mailer) . '</code> mailer at '
            . now()->format('j M Y, g:i A T') . '.</p></div>',
            fn ($message) => $message->to($to)->subject('Business Giseness — test email')
        );
    } catch (\Throwable $e) {
        $this->error('Sending failed: ' . $e->getMessage());
        return 1;
    }

    if (in_array($mailer, ['log', 'array'], true)) {
        $this->warn("Mailer \"{$mailer}\" doesn't deliver email — the message was written to " . ($mailer === 'log' ? 'storage/logs/laravel.log' : 'memory') . '.');
        $this->line('Set MAIL_MAILER=zeptomail in .env (or pass --mailer=zeptomail) to send for real.');
    } else {
        $this->info("Test email sent to {$to}. Check the inbox (and spam folder).");
    }

    return 0;
})->purpose('Send a test email to check the mail setup (e.g. ZeptoMail)');
