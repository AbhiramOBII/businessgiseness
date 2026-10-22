<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MailTestCommandTest extends TestCase
{
    public function test_sends_a_test_email_through_zeptomail(): void
    {
        config([
            'mail.mailers.zeptomail.key' => 'Zoho-enczapikey test-key',
            'mail.mailers.zeptomail.host' => 'cpaas.zoho.in',
            'mail.from.address' => 'noreply@businessgiseness.com',
            'mail.from.name' => 'Business Giseness',
        ]);
        Http::fake(['*' => Http::response(['request_id' => 'req-1'], 201)]);

        $this->artisan('mail:test', ['to' => 'abhiram@example.com', '--mailer' => 'zeptomail'])
            ->expectsOutputToContain('Test email sent to abhiram@example.com')
            ->assertExitCode(0);

        Http::assertSent(fn (Request $r) => $r->url() === 'https://cpaas.zoho.in/v1.1/email'
            && $r->data()['to'][0]['email_address']['address'] === 'abhiram@example.com'
            && $r->data()['subject'] === 'Business Giseness — test email'
            && str_contains($r->data()['htmlbody'], 'Test email sent successfully'));
    }

    public function test_reports_zeptomail_errors(): void
    {
        config(['mail.mailers.zeptomail.key' => 'bad-key', 'mail.from.address' => 'noreply@businessgiseness.com']);
        Http::fake(['*' => Http::response(['error' => ['message' => 'Invalid API Token found']], 401)]);

        $this->artisan('mail:test', ['to' => 'abhiram@example.com', '--mailer' => 'zeptomail'])
            ->expectsOutputToContain('Invalid API Token found')
            ->assertExitCode(1);
    }

    public function test_guards_against_missing_key_and_bad_input(): void
    {
        config(['mail.mailers.zeptomail.key' => null]);
        Http::fake();

        $this->artisan('mail:test', ['to' => 'abhiram@example.com', '--mailer' => 'zeptomail'])
            ->expectsOutputToContain('ZEPTOMAIL_API_KEY is empty')->assertExitCode(1);
        $this->artisan('mail:test', ['to' => 'nope'])->expectsOutputToContain('not a valid email')->assertExitCode(1);
        $this->artisan('mail:test', ['to' => 'a@example.com', '--mailer' => 'pigeon'])->expectsOutputToContain('not configured')->assertExitCode(1);

        Http::assertNothingSent();
    }

    public function test_log_mailer_explains_it_does_not_deliver(): void
    {
        $this->artisan('mail:test', ['to' => 'a@example.com', '--mailer' => 'log'])
            ->expectsOutputToContain("doesn't deliver email")
            ->assertExitCode(0);
    }
}
