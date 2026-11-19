<?php

namespace Tests\Feature;

use App\Models\Programme;
use App\Models\School;
use App\Models\SchoolSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SchoolsAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_public_page_shows_seeded_schools_and_programmes(): void
    {
        $this->get('/schools')->assertOk()
            ->assertSee('School of Law')
            ->assertSee('Bachelor of Laws (LLB)')
            ->assertSee('Certificate in Psychosocial Counselling')
            ->assertSee(config('site.apply.short_courses'));
    }

    public function test_only_admins_can_manage_schools(): void
    {
        $this->get('/admin/schools')->assertRedirect('/login');
        $this->actingAs(User::factory()->create(['role' => 'user']))->get('/admin/schools')->assertForbidden();
    }

    public function test_admin_screens_open(): void
    {
        $this->actingAs($this->admin());
        $this->get('/admin/schools')->assertOk()->assertSee('Trade and Skills Programmes');
        $this->get('/admin/schools/create')->assertOk();
        $this->get('/admin/schools/' . School::first()->id . '/edit')->assertOk()->assertSee('Bachelor of Business Administration (BBA)');
    }

    public function test_admin_can_add_a_school_with_programmes(): void
    {
        $this->actingAs($this->admin())->post('/admin/schools', [
            'name' => 'School of Health Sciences', 'icon' => 'fa-stethoscope', 'intro' => 'Careers in health.',
            'apply_link' => 'undergraduate', 'is_active' => 1,
            'programmes' => [
                ['name' => 'Diploma in Public Health', 'level' => 'Diploma', 'is_active' => 1],
                ['name' => '', 'level' => 'Degree', 'is_active' => 1], // empty row is ignored
            ],
        ])->assertRedirect();

        $school = School::where('name', 'School of Health Sciences')->firstOrFail();
        $this->assertSame(1, $school->programmes()->count());
        $this->get('/schools')->assertSee('School of Health Sciences')->assertSee('Diploma in Public Health');
    }

    public function test_admin_can_edit_hide_and_remove_programmes(): void
    {
        $school = School::where('name', 'School of Law')->firstOrFail();
        $llb = $school->programmes()->firstOrFail();

        $this->actingAs($this->admin())->put("/admin/schools/{$school->id}", [
            'name' => 'School of Law', 'icon' => 'fa-scale-balanced', 'apply_link' => 'undergraduate', 'is_active' => 1,
            'programmes' => [
                ['id' => $llb->id, 'name' => 'Bachelor of Laws (LLB) – Full Time', 'level' => 'Degree', 'sort_order' => 1, 'is_active' => 1],
                ['name' => 'Diploma in Paralegal Studies', 'level' => 'Diploma', 'sort_order' => 2, 'is_active' => 0],
            ],
        ])->assertRedirect("/admin/schools/{$school->id}/edit");

        $this->get('/schools')->assertSee('Bachelor of Laws (LLB) – Full Time')->assertDontSee('Diploma in Paralegal Studies');

        $this->put("/admin/schools/{$school->id}", [
            'name' => 'School of Law', 'icon' => 'fa-scale-balanced', 'apply_link' => 'undergraduate', 'is_active' => 1,
            'programmes' => [['id' => $llb->id, 'name' => 'x', 'level' => 'Degree', 'remove' => 1]],
        ]);
        $this->assertDatabaseMissing('programmes', ['id' => $llb->id]);
        $this->assertDatabaseHas('programmes', ['name' => 'Diploma in Paralegal Studies']);
    }

    public function test_programmes_of_another_school_cannot_be_changed(): void
    {
        $law = School::where('name', 'School of Law')->firstOrFail();
        $other = Programme::where('school_id', '!=', $law->id)->firstOrFail();

        $this->actingAs($this->admin())->put("/admin/schools/{$law->id}", [
            'name' => 'School of Law', 'icon' => 'fa-scale-balanced', 'apply_link' => 'undergraduate', 'is_active' => 1,
            'programmes' => [['id' => $other->id, 'name' => 'x', 'level' => 'Degree', 'remove' => 1]],
        ]);

        $this->assertDatabaseHas('programmes', ['id' => $other->id]);
    }

    public function test_validation_rejects_bad_icon_and_apply_link(): void
    {
        $this->actingAs($this->admin())->post('/admin/schools', [
            'name' => 'Bad', 'icon' => '"><script>', 'apply_link' => 'https://evil.example',
        ])->assertSessionHasErrors(['icon', 'apply_link']);
    }

    public function test_hide_reorder_and_delete_schools(): void
    {
        $this->actingAs($this->admin());
        $law = School::where('name', 'School of Law')->firstOrFail();

        $this->post('/admin/schools/batch', ['schools' => [$law->id => ['sort_order' => 9, 'is_active' => 0]]]);
        $this->get('/schools')->assertDontSee('School of Law');

        $this->delete("/admin/schools/{$law->id}");
        $this->assertDatabaseMissing('schools', ['id' => $law->id]);
        $this->assertDatabaseMissing('programmes', ['school_id' => $law->id]);
    }

    public function test_admin_can_update_page_text_and_catalogue(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($path, "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF");

        $this->actingAs($this->admin())->post('/admin/schools/settings', [
            'header_title' => 'Our Programmes', 'section_title' => 'Choose Your School',
            'catalog_file' => new UploadedFile($path, 'catalog.pdf', 'application/pdf', null, true),
        ])->assertRedirect('/admin/schools');

        $settings = SchoolSetting::current();
        $this->assertStringStartsWith('docs/programmes-catalog-', $settings->catalog_pdf);
        $this->get('/schools')->assertSee('Choose Your School')->assertSee(basename($settings->catalog_pdf));

        @unlink(public_path($settings->catalog_pdf));
    }
}
