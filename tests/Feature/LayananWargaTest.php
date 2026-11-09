<?php
namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\LayananDesaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayananWargaTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_warga_dashboard(): void
    {
        $this->get('/warga')->assertRedirect('/masuk-warga');
    }

    public function test_warga_can_view_seven_categories_after_seed(): void
    {
        $this->seed(LayananDesaSeeder::class);
        $user = User::factory()->create(['role'=>'warga']);
        $this->actingAs($user)->get('/warga')->assertOk()->assertSee('Administrasi Kependudukan');
        $this->assertDatabaseCount('layanan_kategoris',7);
        $this->assertDatabaseCount('layanan_desas',50);
    }

    public function test_warga_cannot_access_petugas_page(): void
    {
        $user = User::factory()->create(['role'=>'warga']);
        $this->actingAs($user)->get('/petugas/pengajuan')->assertForbidden();
    }
}
