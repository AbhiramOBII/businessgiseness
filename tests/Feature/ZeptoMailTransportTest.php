<?php

namespace Tests\Feature;

use App\Mail\CommunityMemberVerification;
use App\Models\CommunityMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ZeptoMailTransportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'mail.default' => 'zeptomail',
            'mail.mailers.zeptomail.host' => 'cpaas.zoho.in',
            'mail.mailers.zeptomail.key' => 'Zoho-enczapikey test-key-123',
            'mail.from.address' => 'noreply@businessgiseness.com',
            'mail.from.name' => 'Business Giseness',
        ]);
    }

    private function member(): CommunityMember
    {
        return CommunityMember::forceCreate([
            'name' => 'Asha Rao', 'email' => 'asha@example.com', 'phone' => '9876543210',
            'company' => 'Rao Foods', 'vertical' => 'Other', 'expectations' => ['events'],
        ]);
    }

    public function test_sends_through_the_zeptomail_api(): void
    {
        Http::fake(['cpaas.zoho.in/*' => Http::response(['data' => [['code' => 'EM_104', 'message' => 'Email request received']], 'request_id' => 'req-abc'], 201)]);

        Mail::to('asha@example.com', 'Asha Rao')->send(new CommunityMemberVerification($this->member()));

        Http::assertSent(function (Request $request) {
            $body = $request->data();

            return $request->url() === 'https://cpaas.zoho.in/v1.1/email'
                && $request->method() === 'POST'
                && $request->header('Authorization')[0] === 'Zoho-enczapikey test-key-123'
                && $body['from'] === ['address' => 'noreply@businessgiseness.com', 'name' => 'Business Giseness']
                && $body['to'] === [['email_address' => ['address' => 'asha@example.com', 'name' => 'Asha Rao']]]
                && $body['subject'] === 'Confirm your email to join BG Community'
                && str_contains($body['htmlbody'], 'Confirm my email')
                && str_contains($body['textbody'], '/bg-community/verify/');
        });
    }

    public function test_key_without_prefix_gets_it_added(): void
    {
        config(['mail.mailers.zeptomail.key' => 'bare-key']);
        Http::fake(['*' => Http::response(['request_id' => 'x'], 201)]);

        Mail::raw('Hello', fn ($m) => $m->to('a@example.com')->subject('Hi'));

        Http::assertSent(fn (Request $r) => $r->header('Authorization')[0] === 'Zoho-enczapikey bare-key' && $r->data()['textbody'] === 'Hello');
    }

    public function test_api_errors_surface_as_a_failed_send(): void
    {
        Http::fake(['*' => Http::response(['error' => ['code' => 'TM_3601', 'details' => [['message' => 'Sender address not verified']]]], 400)]);

        try {
            Mail::raw('Hello', fn ($m) => $m->to('a@example.com')->subject('Hi'));
            $this->fail('Expected the send to fail');
        } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
            $this->assertStringContainsString('Sender address not verified', $e->getMessage());
        }
    }

    public function test_registration_reports_a_zeptomail_failure_to_the_visitor(): void
    {
        Http::fake(['*' => Http::response(['error' => ['message' => 'Invalid API key']], 401)]);

        $this->post('/bg-community/join', [
            'name' => 'Asha Rao', 'email' => 'asha@example.com', 'phone' => '+91 98765 43210',
            'company' => 'Rao Foods', 'vertical' => 'Other', 'expectations' => ['events'],
        ])->assertSessionHas('member_mail_failed');

        $this->assertSame(1, CommunityMember::count());
    }
}
