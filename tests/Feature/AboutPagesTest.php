<?php

namespace Tests\Feature;

use App\Models\AboutItem;
use App\Models\AboutPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AboutPagesTest extends TestCase
{
    use RefreshDatabase;

    /** A real 1x1 PNG, so the image rule passes without the GD extension. */
    private function pngUpload(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'png');
        file_put_contents($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_public_about_pages_show_seeded_content(): void
    {
        $this->get('/about')->assertOk()->assertSee('Our Mission');
        $this->get('/about/team')->assertOk()->assertSee('Registrar')->assertSee('Mr. Machila');
        $this->get('/about/partnerships')->assertOk()->assertSee('Research Partners');
        $this->get('/about/accreditation')->assertOk()->assertSee('TEVETA Accreditation')->assertSee('Quality Assurance');
    }

    public function test_non_admins_cannot_open_the_module(): void
    {
        $this->get('/admin/about')->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'user']))->get('/admin/about')->assertForbidden();
    }

    public function test_admin_can_open_every_screen(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->get('/admin/about')->assertOk()->assertSee('Accreditation');

        foreach (AboutPage::PAGES as $slug => $config) {
            $this->get("/admin/about/$slug")->assertOk()->assertSee($config['label']);
            foreach (array_keys($config['groups']) as $group) {
                $this->get("/admin/about/$slug/$group/create")->assertOk();
            }
        }

        $this->get('/admin/about/overview/hods/create')->assertNotFound();
        $this->get('/admin/about/items/' . AboutItem::first()->id . '/edit')->assertOk();
    }

    public function test_admin_can_edit_page_text(): void
    {
        $this->actingAs($this->admin())->put('/admin/about/accreditation', [
            'header_title'  => 'Accreditation',
            'section_title' => 'Recognised Qualifications',
            'extra_title'   => 'Quality First',
            'extra_text'    => 'We review every programme every year.',
        ])->assertRedirect('/admin/about/accreditation');

        $this->get('/about/accreditation')->assertSee('Recognised Qualifications')->assertSee('Quality First');
    }

    public function test_admin_can_add_edit_hide_and_delete_a_card(): void
    {
        $this->actingAs($this->admin())->post('/admin/about/partnerships/cards', [
            'title' => 'Mining Partners', 'body' => 'Work placements on the Copperbelt.', 'icon' => 'fa-industry', 'is_active' => 1,
        ])->assertRedirect('/admin/about/partnerships');
        $this->get('/about/partnerships')->assertSee('Mining Partners');

        $card = AboutItem::where('title', 'Mining Partners')->firstOrFail();
        $this->put("/admin/about/items/{$card->id}", [
            'title' => 'Mining Partners', 'body' => 'Updated text.', 'icon' => 'fa-gem', 'is_active' => 1,
        ]);
        $this->get('/about/partnerships')->assertSee('Updated text.');

        $this->post('/admin/about/partnerships/batch', ['items' => [$card->id => ['sort_order' => 1, 'is_active' => 0]]]);
        $this->get('/about/partnerships')->assertDontSee('Mining Partners');

        $this->delete("/admin/about/items/{$card->id}");
        $this->assertDatabaseMissing('about_items', ['id' => $card->id]);
    }

    public function test_card_icon_must_be_a_font_awesome_class(): void
    {
        $this->actingAs($this->admin())->post('/admin/about/overview/cards', [
            'title' => 'Bad', 'body' => 'Text', 'icon' => '"><script>',
        ])->assertSessionHasErrors('icon');
    }

    public function test_admin_can_add_a_person_with_a_photo(): void
    {
        $this->actingAs($this->admin())->post('/admin/about/team/hods', [
            'title' => 'Ms. Phiri', 'subtitle' => 'Head – School of Law', 'is_active' => 1,
            'image_file' => $this->pngUpload('phiri.png'),
        ])->assertRedirect('/admin/about/team');

        $person = AboutItem::where('title', 'Ms. Phiri')->firstOrFail();
        $this->assertStringStartsWith('images/about/hods_', $person->image);
        $this->assertStringEndsWith('.png', $person->image);
        $this->assertFileExists(public_path($person->image));
        $this->get('/about/team')->assertSee('Head – School of Law');

        $this->delete("/admin/about/items/{$person->id}");
        $this->assertFileDoesNotExist(public_path($person->image));
    }
}
