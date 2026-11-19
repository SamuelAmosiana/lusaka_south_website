<?php

namespace Tests\Feature;

use App\Models\AboutItem;
use App\Models\GalleryPhoto;
use App\Models\GallerySection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GalleryResourcesContactAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    /** A real 1x1 PNG, so the image rule passes without the GD extension. */
    private function png(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'png');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }

    public function test_public_pages_show_seeded_content(): void
    {
        $this->get('/gallery')->assertOk()->assertSee('Campus Facilities')->assertSee('Computer Laboratory');
        $this->get('/e-resources')->assertOk()->assertSee('Khan Academy')->assertSee('https://www.khanacademy.org', false);
        $this->get('/contact')->assertOk()->assertSee('Finance &amp; Accounts', false)->assertSee('finance@lsc.edu.zm')->assertSee('Send Us a Message');
    }

    public function test_only_admins_can_open_the_new_sections(): void
    {
        $this->get('/admin/gallery')->assertRedirect('/login');
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get('/admin/gallery')->assertForbidden();
        $this->actingAs($user)->get('/admin/about/contact')->assertForbidden();
    }

    public function test_admin_screens_open(): void
    {
        $this->actingAs($this->admin());
        $this->get('/admin/gallery')->assertOk()->assertSee('Student Life');
        $this->get('/admin/gallery/create')->assertOk();
        $this->get('/admin/gallery/' . GallerySection::first()->id . '/edit')->assertOk()->assertSee('Computer Laboratory');
        $this->get('/admin/about/e-resources')->assertOk()->assertSee('OpenStax');
        $this->get('/admin/about/contact')->assertOk()->assertSee('Admissions Office');
        // E-Resources and Contact are not listed among the About pages
        $this->get('/admin/about')->assertOk()->assertDontSee('Department Contact Cards')->assertDontSee('Resource Links');
    }

    public function test_admin_can_create_a_gallery_section_with_photos(): void
    {
        $this->actingAs($this->admin())->post('/admin/gallery', [
            'title' => 'Graduation 2026', 'subtitle' => 'Our graduates', 'is_active' => 1,
            'new_photos'   => [$this->png('graduation-day.png'), $this->png('the_ceremony.png')],
            'new_captions' => ['', 'Ceremony Hall'],
        ])->assertRedirect();

        $section = GallerySection::where('title', 'Graduation 2026')->firstOrFail();
        $photos = $section->photos;
        $this->assertCount(2, $photos);
        $this->assertSame('Graduation Day', $photos[0]->caption); // from the file name
        $this->assertSame('Ceremony Hall', $photos[1]->caption);
        $this->assertStringStartsWith('images/gallery/', $photos[0]->image);

        $this->get('/gallery')->assertSee('Graduation 2026')->assertSee('Ceremony Hall');

        $this->delete("/admin/gallery/{$section->id}");
        $this->assertFileDoesNotExist(public_path($photos[0]->image));
        $this->assertDatabaseCount('gallery_photos', 16); // only the seeded photos remain
    }

    public function test_admin_can_caption_hide_and_remove_photos(): void
    {
        $section = GallerySection::where('title', 'Student Life')->firstOrFail();
        [$first, $second] = $section->photos->take(2)->all();

        $this->actingAs($this->admin())->put("/admin/gallery/{$section->id}", [
            'title' => 'Student Life', 'is_active' => 1,
            'photos' => [
                $first->id  => ['caption' => 'Culture Week', 'sort_order' => 1, 'is_active' => 1],
                $second->id => ['caption' => $second->caption, 'sort_order' => 2, 'is_active' => 0],
            ],
        ])->assertRedirect("/admin/gallery/{$section->id}/edit");

        $this->get('/gallery')->assertSee('Culture Week')->assertDontSee($second->caption);

        $this->put("/admin/gallery/{$section->id}", [
            'title' => 'Student Life', 'is_active' => 1,
            'photos' => [$first->id => ['remove' => 1]],
        ]);
        $this->assertDatabaseMissing('gallery_photos', ['id' => $first->id]);
        // Original site photos are not deleted from disk
        $this->assertFileExists(public_path($first->image));
    }

    public function test_photos_of_another_section_cannot_be_removed(): void
    {
        $section = GallerySection::where('title', 'Student Life')->firstOrFail();
        $other = GalleryPhoto::where('gallery_section_id', '!=', $section->id)->firstOrFail();

        $this->actingAs($this->admin())->put("/admin/gallery/{$section->id}", [
            'title' => 'Student Life', 'is_active' => 1,
            'photos' => [$other->id => ['remove' => 1]],
        ]);
        $this->assertDatabaseHas('gallery_photos', ['id' => $other->id]);
    }

    public function test_gallery_rejects_non_images(): void
    {
        $this->actingAs($this->admin())->post('/admin/gallery', [
            'title' => 'Bad', 'new_photos' => [UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')],
        ])->assertSessionHasErrors('new_photos.0');
    }

    public function test_admin_can_add_an_e_resource_link(): void
    {
        $this->actingAs($this->admin())->post('/admin/about/e-resources/cards', [
            'title' => 'JSTOR', 'body' => 'Journals and books.', 'icon' => 'fa-book', 'url' => 'https://www.jstor.org', 'subtitle' => 'Visit JSTOR', 'is_active' => 1,
        ])->assertRedirect('/admin/about/e-resources');

        $this->get('/e-resources')->assertSee('JSTOR')->assertSee('https://www.jstor.org', false)->assertSee('Visit JSTOR');
    }

    public function test_e_resource_link_must_be_a_web_address(): void
    {
        $this->actingAs($this->admin())->post('/admin/about/e-resources/cards', [
            'title' => 'Bad', 'body' => 'x', 'icon' => 'fa-book', 'url' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('url');
    }

    public function test_admin_can_edit_contact_cards_and_form_heading(): void
    {
        $this->actingAs($this->admin());
        $finance = AboutItem::where('page', 'contact')->where('title', 'Finance & Accounts')->firstOrFail();

        $this->put("/admin/about/items/{$finance->id}", [
            'title' => 'Bursar', 'icon' => 'fa-building-columns', 'phone' => '+260 211 292292', 'email' => 'bursar@lsc.edu.zm',
            'body' => "Fees and payments.\nOpen weekdays.", 'is_active' => 1,
        ])->assertRedirect('/admin/about/contact');

        $this->put('/admin/about/contact', [
            'header_title' => 'Contact Us', 'section_title' => 'Get In Touch', 'extra_title' => 'Write to Us',
        ]);

        $this->get('/contact')->assertSee('Bursar')->assertSee('bursar@lsc.edu.zm')->assertSee('tel:+260211292292', false)->assertSee('Write to Us');
    }

    public function test_contact_card_validates_email_and_phone(): void
    {
        $this->actingAs($this->admin())->post('/admin/about/contact/cards', [
            'title' => 'Bad', 'icon' => 'fa-phone', 'email' => 'not-an-email', 'phone' => '<script>',
        ])->assertSessionHasErrors(['email', 'phone']);
    }
}
