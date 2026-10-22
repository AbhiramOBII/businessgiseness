<?php

namespace Tests\Feature;

use App\Mail\CommunityMemberVerification;
use App\Models\CommunityMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class CommunityMemberTest extends TestCase
{
    use RefreshDatabase;

    private function register(array $overrides = [])
    {
        return $this->post('/bg-community/join', array_merge([
            'name' => 'Asha Rao',
            'email' => 'Asha@Example.com',
            'phone' => '+91 98765 43210',
            'company' => 'Rao Foods',
            'vertical' => 'Food & Beverage',
            'expectations' => ['customers', 'vendors'],
            'message' => 'Looking for packaging vendors.',
        ], $overrides));
    }

    private function admin(): User
    {
        return User::factory()->create()->forceFill(['is_admin' => true]);
    }

    public function test_join_form_is_on_the_community_page(): void
    {
        $this->get('/bg-community')->assertOk()
            ->assertSee('Register to join BG Community')
            ->assertSee('Vertical')
            ->assertSee('What do you expect from BG Community?')
            ->assertSee('Find vendors &amp; suppliers', false);
    }

    public function test_registration_saves_member_and_sends_verification_email(): void
    {
        Mail::fake();

        $this->register()->assertRedirect(route('community.index') . '#join')->assertSessionHas('member_pending', 'asha@example.com');

        $member = CommunityMember::firstOrFail();
        $this->assertSame('asha@example.com', $member->email); // normalised
        $this->assertSame(['customers', 'vendors'], $member->expectations);
        $this->assertSame(['Find new customers', 'Find vendors & suppliers'], $member->expectation_labels);
        $this->assertFalse($member->isVerified());
        $this->assertNotNull($member->verification_sent_at);

        Mail::assertSent(CommunityMemberVerification::class, fn ($mail) => $mail->hasTo('asha@example.com'));

        $this->get('/bg-community')->assertSee('Check your email');
    }

    public function test_verification_link_confirms_the_member(): void
    {
        Mail::fake();
        $this->register();
        $member = CommunityMember::firstOrFail();

        $mail = null;
        Mail::assertSent(CommunityMemberVerification::class, function ($m) use (&$mail) { $mail = $m; return true; });
        $rendered = $mail->render();
        preg_match('#href="([^"]+/bg-community/verify/[^"]+)"#', $rendered, $match);
        $link = html_entity_decode($match[1]);
        $this->assertStringContainsString('signature=', $link);
        $this->assertStringContainsString('Confirm my email', $rendered);

        $this->get($link)->assertOk()->assertSee("You're in, Asha Rao!", false);
        $this->assertTrue($member->fresh()->isVerified());

        // Clicking again is harmless
        $this->get($link)->assertOk();
    }

    public function test_tampered_or_expired_links_are_rejected(): void
    {
        $member = CommunityMember::forceCreate([
            'name' => 'Asha', 'email' => 'asha@example.com', 'phone' => '9876543210',
            'company' => 'Rao Foods', 'vertical' => 'Other', 'expectations' => ['events'],
        ]);

        $this->get("/bg-community/verify/{$member->id}/" . sha1('asha@example.com'))->assertForbidden()->assertSee('expired');

        $wrongHash = URL::temporarySignedRoute('community.members.verify', now()->addDay(), ['member' => $member->id, 'hash' => sha1('someone@else.com')]);
        $this->get($wrongHash)->assertForbidden();

        $expired = URL::temporarySignedRoute('community.members.verify', now()->subMinute(), ['member' => $member->id, 'hash' => sha1('asha@example.com')]);
        $this->get($expired)->assertForbidden();

        $this->assertFalse($member->fresh()->isVerified());
    }

    public function test_validation(): void
    {
        Mail::fake();

        $this->register([
            'name' => '', 'email' => 'nope', 'phone' => 'abc', 'company' => '',
            'vertical' => 'Spaceships', 'expectations' => [],
        ])->assertSessionHasErrors(['name', 'email', 'phone', 'company', 'vertical', 'expectations']);

        $this->register(['expectations' => ['world-domination']])->assertSessionHasErrors('expectations.0');

        $this->assertSame(0, CommunityMember::count());
        Mail::assertNothingSent();
    }

    public function test_re_registering_updates_details_and_does_not_duplicate(): void
    {
        Mail::fake();

        $this->register();
        $this->travel(5)->minutes();
        $this->register(['company' => 'Rao Foods Pvt Ltd']);

        $this->assertSame(1, CommunityMember::count());
        $this->assertSame('Rao Foods Pvt Ltd', CommunityMember::first()->company);
        Mail::assertSent(CommunityMemberVerification::class, 2);

        // Within two minutes: no extra email
        $this->register();
        Mail::assertSent(CommunityMemberVerification::class, 2);
    }

    public function test_confirmed_members_are_told_they_already_belong(): void
    {
        Mail::fake();
        $this->register();
        CommunityMember::first()->forceFill(['email_verified_at' => now()])->save();

        $this->register(['company' => 'Changed'])->assertSessionHas('member_exists');
        $this->assertSame('Rao Foods', CommunityMember::first()->company);
        Mail::assertSent(CommunityMemberVerification::class, 1);
    }

    public function test_honeypot_drops_bots(): void
    {
        Mail::fake();
        $this->register(['website' => 'spam'])->assertRedirect();
        $this->assertSame(0, CommunityMember::count());
        Mail::assertNothingSent();
    }

    public function test_mail_failure_is_reported_and_details_kept(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->register()->assertSessionHas('member_mail_failed');
        $this->assertSame(1, CommunityMember::count());
        $this->assertNull(CommunityMember::first()->verification_sent_at);
    }

    public function test_admin_lists_filters_exports_resends_and_removes_members(): void
    {
        Mail::fake();
        $this->register();
        $this->register(['name' => 'Ravi', 'email' => 'ravi@example.com', 'company' => 'Ravi Tech', 'vertical' => 'Technology & IT Services', 'expectations' => ['partners']]);
        CommunityMember::where('email', 'ravi@example.com')->first()->forceFill(['email_verified_at' => now()])->save();
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/community-members')->assertOk()
            ->assertSee('Asha Rao')->assertSee('Ravi Tech')->assertSee('Awaiting confirmation')->assertSee('Confirmed')
            ->assertSee('Looking for packaging vendors.');

        $this->actingAs($admin)->get('/admin/community-members?status=verified')->assertSee('Ravi Tech')->assertDontSee('Rao Foods');
        $this->actingAs($admin)->get('/admin/community-members?vertical=Food+%26+Beverage')->assertSee('Rao Foods')->assertDontSee('Ravi Tech');
        $this->actingAs($admin)->get('/admin/community-members?expects=partners')->assertSee('Ravi Tech')->assertDontSee('Rao Foods');

        $csv = $this->actingAs($admin)->get('/admin/community-members/export')->assertOk()->streamedContent();
        $this->assertStringContainsString('Name,Email,Phone,Company,Vertical,Expectations', $csv);
        $this->assertStringContainsString('"Find new customers; Find vendors & suppliers"', $csv);

        $asha = CommunityMember::where('email', 'asha@example.com')->first();
        $this->actingAs($admin)->post("/admin/community-members/{$asha->id}/resend")->assertSessionHas('success');
        Mail::assertSent(CommunityMemberVerification::class, 3);

        $this->actingAs($admin)->delete("/admin/community-members/{$asha->id}")->assertRedirect();
        $this->assertSame(1, CommunityMember::count());

        $this->actingAs($admin)->get('/admin/community-events')->assertSee('Members');
    }

    public function test_admin_member_pages_require_admin(): void
    {
        $this->get('/admin/community-members')->assertRedirect();
        $this->actingAs(User::factory()->create())->get('/admin/community-members/export')->assertRedirect();
    }
}
