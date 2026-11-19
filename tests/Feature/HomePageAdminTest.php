<?php

namespace Tests\Feature;

use App\Models\AboutItem;
use App\Models\AboutPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class HomePageAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    /** A real 1x1 PNG, so the image rule passes without the GD extension. */
    private function png(string $name = 'pic.png'): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'png');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }

    public function test_home_page_shows_seeded_sections(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('Our Core Values')->assertSee('Integrity')
            ->assertSee('Kenneth D. Kaunda Award')->assertSee('350+')
            ->assertSee('State-of-the-Art Facilities')
            ->assertSee('Science Laboratory')
            ->assertSee('To talk to Enrolments, Call');
    }

    public function test_only_admins_can_open_the_hub(): void
    {
        $this->get('/admin/home')->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'user']))->get('/admin/home')->assertForbidden();
    }

    public function test_hub_lists_every_section_and_links_work(): void
    {
        $this->actingAs($this->admin());
        $response = $this->get('/admin/home')->assertOk();

        foreach (['Hero Banner', 'Home Counters', 'Core Values', 'Online &amp; Campus', 'Latest Updates', 'Scholarships', 'Our Campus', 'School Environment', 'Get in Touch'] as $label) {
            $response->assertSee($label, false);
        }
        $response->assertSee(route('admin.counters.index'))->assertSee(route('admin.learning.index'));

        // The sidebar item is now "Home Page", not "Home Counters"
        $response->assertSee('Home Page')->assertDontSee('> Home Counters</a>', false);

        foreach (AboutPage::slugsIn('home') as $slug) {
            $this->get("/admin/about/$slug")->assertOk()->assertSee('All Home Page Sections');
            foreach (array_keys(AboutPage::PAGES[$slug]['groups']) as $group) {
                $this->get("/admin/about/$slug/$group/create")->assertOk();
            }
        }
        $this->get('/admin/counters')->assertOk()->assertSee('All Home Page Sections');
        $this->get('/admin/learning')->assertOk()->assertSee('All Home Page Sections');
    }

    public function test_admin_can_edit_hero_text_and_picture(): void
    {
        $this->actingAs($this->admin())->put('/admin/about/home-hero', [
            'header_title' => 'Welcome to LSUC', 'header_subtitle' => 'Dream Big', 'section_subtitle' => 'Study with us.',
            'image_file' => $this->png('cover.png'),
        ])->assertRedirect('/admin/about/home-hero');

        $hero = AboutPage::where('slug', 'home-hero')->first();
        $this->assertStringStartsWith('images/about/home-hero_', $hero->image);
        $this->get('/')->assertSee('Welcome to LSUC')->assertSee('Dream Big')->assertSee($hero->image);

        @unlink(public_path($hero->image));
    }

    public function test_hero_heading_is_required_and_other_fields_cannot_be_injected(): void
    {
        $this->actingAs($this->admin())->put('/admin/about/home-hero', [
            'header_title' => '', 'slug' => 'hacked',
        ])->assertSessionHasErrors('header_title');
        $this->assertDatabaseHas('about_pages', ['slug' => 'home-hero']);
    }

    public function test_admin_can_add_a_scholarship_award_and_figure(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/about/home-scholarships/awards', [
            'title' => 'Michael C. Sata Award', 'value' => '30%', 'subtitle' => 'ODL & Skills', 'body' => 'For distance learning.', 'is_active' => 1,
        ])->assertRedirect('/admin/about/home-scholarships');
        $this->post('/admin/about/home-scholarships/stats', ['title' => '1,200', 'subtitle' => 'Students Supported', 'is_active' => 1]);

        $this->get('/')->assertSee('Michael C. Sata Award')->assertSee('30%')->assertSee('Students Supported');

        $this->post('/admin/about/home-scholarships/awards', ['title' => 'No Value'])->assertSessionHasErrors(['value', 'body']);
    }

    public function test_campus_slide_needs_a_picture(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/about/home-campus/slides', ['title' => 'Library', 'is_active' => 1])->assertSessionHasErrors('image_file');

        $this->post('/admin/about/home-campus/slides', [
            'title' => 'New Library', 'body' => 'Opened in 2026', 'is_active' => 1, 'image_file' => $this->png('library.png'),
        ])->assertRedirect('/admin/about/home-campus');

        $slide = AboutItem::where('title', 'New Library')->firstOrFail();
        $this->get('/')->assertSee('New Library')->assertSee($slide->image);

        // Editing a slide without choosing a new picture keeps the current one
        $this->put("/admin/about/items/{$slide->id}", ['title' => 'Renamed Library', 'is_active' => 1])->assertSessionHasNoErrors();
        $this->assertSame($slide->image, $slide->fresh()->image);

        $this->delete("/admin/about/items/{$slide->id}");
        $this->assertFileDoesNotExist(public_path($slide->image));
    }

    public function test_environment_photo_caption_is_optional(): void
    {
        $this->actingAs($this->admin())->post('/admin/about/home-environment/photos', [
            'title' => '', 'is_active' => 1, 'image_file' => $this->png('grounds.png'),
        ])->assertSessionHasNoErrors();

        $photo = AboutItem::where('page', 'home-environment')->latest('id')->first();
        $this->assertSame('', $photo->title);
        $this->get('/')->assertSee($photo->image);
        @unlink(public_path($photo->image));
    }

    public function test_hiding_all_core_values_hides_the_section(): void
    {
        $this->actingAs($this->admin());
        $ids = AboutItem::where('page', 'home-values')->pluck('id');
        $this->post('/admin/about/home-values/batch', ['items' => $ids->mapWithKeys(fn ($id) => [$id => ['is_active' => 0]])->all()]);

        $this->get('/')->assertOk()->assertDontSee('Our Core Values');
    }

    public function test_get_in_touch_details_turn_phones_and_emails_into_links(): void
    {
        $detail = AboutItem::where('page', 'home-contact')->where('title', 'Office Hours')->firstOrFail();

        $this->actingAs($this->admin())->put("/admin/about/items/{$detail->id}", [
            'title' => 'Office Hours', 'icon' => 'fa-clock', 'is_active' => 1,
            'body' => "Monday - Friday: 8:00 AM - 5:00 PM\nSaturday: 8:00 AM - 1:00 PM\nAfter hours:\n+260 977 000000\nwhatsapp@lsc.edu.zm",
        ])->assertRedirect('/admin/about/home-contact');

        $this->get('/')->assertSee('Saturday: 8:00 AM - 1:00 PM')
            ->assertSee('href="tel:+260977000000"', false)
            ->assertSee('href="mailto:whatsapp@lsc.edu.zm"', false);
    }
}
