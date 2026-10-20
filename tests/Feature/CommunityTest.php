<?php

namespace Tests\Feature;

use App\Models\CommunityEvent;
use App\Models\CommunityRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create()->forceFill(['is_admin' => true]);
    }

    private function meetup(array $attributes = []): CommunityEvent
    {
        return CommunityEvent::create(array_merge([
            'title' => 'BG Meetup November',
            'slug' => 'bg-meetup-november',
            'summary' => 'Meet founders, customers and vendors.',
            'starts_at' => now()->addWeek()->setTime(18, 0),
            'ends_at' => now()->addWeek()->setTime(20, 30),
            'venue_name' => 'Obii Studios',
            'venue_address' => 'Banashankari, Bengaluru',
            'registration_open' => true,
            'is_published' => true,
        ], $attributes));
    }

    private function signup(CommunityEvent $event, array $overrides = [])
    {
        return $this->post("/bg-community/{$event->slug}/register", array_merge([
            'name' => 'Asha Rao',
            'email' => 'asha@example.com',
            'company' => 'Rao Foods',
            'phone' => '+91 98765 43210',
        ], $overrides));
    }

    public function test_admin_creates_meetup_and_it_appears_publicly(): void
    {
        $this->actingAs($this->admin())->post('/admin/community-events', [
            'title' => 'Founders Evening Bengaluru',
            'summary' => 'Networking for MSME owners.',
            'description' => "Agenda:\n6pm intros\n7pm open networking",
            'starts_at' => now()->addDays(10)->format('Y-m-d\TH:i'),
            'venue_name' => 'Obii Studios',
            'capacity' => 30,
            'registration_open' => '1',
            'is_published' => '1',
        ])->assertRedirect();

        $event = CommunityEvent::firstOrFail();
        $this->assertSame('founders-evening-bengaluru', $event->slug);

        $this->get('/bg-community')->assertOk()->assertSee('Founders Evening Bengaluru')->assertSee('BG Community');

        $html = $this->get('/bg-community/founders-evening-bengaluru')->assertOk()
            ->assertSee('Save your seat')
            ->assertSee('30 of 30 seats left')
            ->getContent();

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
        $types = array_map(fn ($json) => json_decode($json, true)['@type'] ?? 'graph', $m[1]);
        $this->assertContains('Event', $types);

        $this->get('/sitemap.xml')->assertSee(route('community.show', $event), false)->assertSee(route('community.index'), false);
    }

    public function test_member_can_sign_up(): void
    {
        $event = $this->meetup();

        $this->signup($event)->assertRedirect("/bg-community/{$event->slug}")->assertSessionHas('registered');

        $this->assertDatabaseHas('community_registrations', [
            'community_event_id' => $event->id,
            'name' => 'Asha Rao',
            'email' => 'asha@example.com',
            'company' => 'Rao Foods',
            'phone' => '+91 98765 43210',
        ]);

        $this->get("/bg-community/{$event->slug}")->assertSee("You're on the list", false);
    }

    public function test_sign_up_validation_and_duplicates(): void
    {
        $event = $this->meetup();

        $this->signup($event, ['name' => '', 'email' => 'not-an-email', 'company' => '', 'phone' => 'abc'])
            ->assertSessionHasErrors(['name', 'email', 'company', 'phone']);

        $this->signup($event);
        $this->signup($event, ['name' => 'Asha Again'])->assertSessionHasErrors('email');
        $this->assertSame(1, CommunityRegistration::count());

        // Same person can join a different meetup
        $other = $this->meetup(['title' => 'December', 'slug' => 'december']);
        $this->signup($other)->assertSessionHasNoErrors();
        $this->assertSame(2, CommunityRegistration::count());
    }

    public function test_capacity_closed_past_and_draft_meetups_reject_sign_ups(): void
    {
        $full = $this->meetup(['slug' => 'full', 'capacity' => 1]);
        $this->signup($full, ['email' => 'first@example.com'])->assertSessionHas('registered');
        $this->signup($full, ['email' => 'second@example.com'])->assertSessionHas('closed');
        $this->get('/bg-community/full')->assertSee('fully booked');

        $closed = $this->meetup(['slug' => 'closed', 'registration_open' => false]);
        $this->signup($closed)->assertSessionHas('closed');

        $past = $this->meetup(['slug' => 'past', 'starts_at' => now()->subWeek(), 'ends_at' => now()->subWeek()->addHours(2)]);
        $this->signup($past)->assertSessionHas('closed');
        $this->get('/bg-community')->assertSee('Past meetups');

        $draft = $this->meetup(['slug' => 'draft', 'is_published' => false]);
        $this->get('/bg-community/draft')->assertNotFound();
        $this->signup($draft)->assertNotFound();
        $this->actingAs($this->admin())->get('/bg-community/draft')->assertOk()->assertSee('Draft');

        $this->assertSame(1, CommunityRegistration::count());
    }

    public function test_honeypot_silently_drops_bots(): void
    {
        $event = $this->meetup();

        $this->signup($event, ['website' => 'http://spam.example'])->assertRedirect()->assertSessionHas('registered');
        $this->assertSame(0, CommunityRegistration::count());
    }

    public function test_admin_sees_signups_exports_csv_and_removes_one(): void
    {
        $event = $this->meetup();
        $this->signup($event);
        $this->signup($event, ['name' => 'Ravi', 'email' => 'ravi@example.com', 'company' => '=HYPERLINK("x")']);
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/community-events')->assertOk()->assertSee('BG Meetup November');
        $this->actingAs($admin)->get("/admin/community-events/{$event->slug}")->assertOk()
            ->assertSee('Asha Rao')->assertSee('Rao Foods')->assertSee('ravi@example.com');

        $csv = $this->actingAs($admin)->get("/admin/community-events/{$event->slug}/export")->assertOk()->streamedContent();
        $this->assertStringContainsString('Name,Email,Company,Phone', $csv);
        $this->assertStringContainsString('"Asha Rao",asha@example.com,"Rao Foods","+91 98765 43210"', $csv);
        $this->assertStringContainsString("'=HYPERLINK", $csv); // formula injection neutralised

        $registration = CommunityRegistration::where('email', 'ravi@example.com')->first();
        $this->actingAs($admin)->delete("/admin/community-events/{$event->slug}/registrations/{$registration->id}")->assertRedirect();
        $this->assertSame(1, $event->registrations()->count());
    }

    public function test_old_community_urls_redirect_to_bg_community(): void
    {
        $event = $this->meetup();

        $this->get('/community')->assertStatus(301)->assertRedirect('/bg-community');
        $this->get("/community/{$event->slug}")->assertStatus(301)->assertRedirect("/bg-community/{$event->slug}");
        $this->assertSame(url('/bg-community'), route('community.index'));
    }

    public function test_admin_pages_require_admin(): void
    {
        $event = $this->meetup();

        $this->get('/admin/community-events')->assertRedirect();
        $this->actingAs(User::factory()->create())->get("/admin/community-events/{$event->slug}/export")->assertRedirect();
    }
}
