<?php

namespace Tests\Feature;

use App\Models\Kelompok;
use App\Models\Semester;
use App\Models\TahunAkademik;
use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class AcademicFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $dpl;
    protected $angkatan;
    protected $activeTahun;
    protected $activeSemester;
    protected $inactiveTahun;
    protected $inactiveSemester;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'dpl']);
        Role::create(['name' => 'mahasiswa']);

        // Setup Angkatan (Legacy but required by DB)
        $this->angkatan = \DB::table('angkatan')->insertGetId([
            'nama_angkatan' => 'Angkatan 2023',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonths(6),
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Setup Academic Periods
        $this->activeTahun = TahunAkademik::create(['nama' => '2023/2024', 'is_aktif' => true]);
        $this->activeSemester = Semester::create(['nama' => 'Ganjil', 'is_aktif' => true]);

        $this->inactiveTahun = TahunAkademik::create(['nama' => '2024/2025', 'is_aktif' => false]);
        $this->inactiveSemester = Semester::create(['nama' => 'Genap', 'is_aktif' => false]);

        // Setup Users
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->dpl = User::factory()->create();
        $this->dpl->assignRole('dpl');
    }

    /** @test */
    public function users_index_filters_mahasiswa_by_active_period_but_shows_admin()
    {
        // Student in active period
        $activeStudent = User::factory()->create([
            'tahun_akademik_id' => $this->activeTahun->id,
            'semester_id' => $this->activeSemester->id,
        ]);
        $activeStudent->assignRole('mahasiswa');

        // Student in inactive period
        $inactiveStudent = User::factory()->create([
            'tahun_akademik_id' => $this->inactiveTahun->id,
            'semester_id' => $this->inactiveSemester->id,
        ]);
        $inactiveStudent->assignRole('mahasiswa');

        $response = $this->actingAs($this->admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee($activeStudent->name);
        $response->assertDontSee($inactiveStudent->name);
        $response->assertSee($this->admin->name); // Non-mahasiswa should always be seen
    }

    /** @test */
    public function groups_index_filters_by_active_period()
    {
        $lokasi = Lokasi::create([
            'nama_desa' => 'Test Desa',
            'nama_kecamatan' => 'Test Kec',
            'nama_kabupaten' => 'Test Kab',
            'nama_provinsi' => 'Test Prov',
            'latitude' => 0,
            'longitude' => 0
        ]);

        // Group in active period
        $activeGroup = Kelompok::create([
            'nama_kelompok' => 'Active Group',
            'angkatan_id' => $this->angkatan,
            'tahun_akademik_id' => $this->activeTahun->id,
            'semester_id' => $this->activeSemester->id,
            'lokasi_id' => $lokasi->id,
            'dpl_id' => $this->dpl->id,
        ]);

        // Group in inactive period
        $inactiveGroup = Kelompok::create([
            'nama_kelompok' => 'Inactive Group',
            'angkatan_id' => $this->angkatan,
            'tahun_akademik_id' => $this->inactiveTahun->id,
            'semester_id' => $this->inactiveSemester->id,
            'lokasi_id' => $lokasi->id,
            'dpl_id' => $this->dpl->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('groups.index'));

        $response->assertStatus(200);
        $response->assertSee($activeGroup->nama_kelompok);
        $response->assertDontSee($inactiveGroup->nama_kelompok);
    }

    /** @test */
    public function assigning_student_to_group_syncs_academic_period()
    {
        $lokasi = Lokasi::create([
            'nama_desa' => 'Test Desa',
            'nama_kecamatan' => 'Test Kec',
            'nama_kabupaten' => 'Test Kab',
            'nama_provinsi' => 'Test Prov',
            'latitude' => 0,
            'longitude' => 0
        ]);
        
        $group = Kelompok::create([
            'nama_kelompok' => 'Target Group',
            'angkatan_id' => $this->angkatan,
            'tahun_akademik_id' => $this->activeTahun->id,
            'semester_id' => $this->activeSemester->id,
            'lokasi_id' => $lokasi->id,
            'dpl_id' => $this->dpl->id,
        ]);

        $student = User::factory()->create();
        $student->assignRole('mahasiswa');

        // Assignment via UserController update logic (simulated)
        $response = $this->actingAs($this->admin)->put(route('users.update', $student), [
            'name' => $student->name,
            'email' => $student->email,
            'role' => 'mahasiswa',
            'nim' => '123456789',
            'jurusan' => 'informatika',
            'kelompok_id' => $group->id,
        ]);

        $student->refresh();
        $this->assertEquals($group->id, $student->kelompok_id);
        $this->assertEquals($this->activeTahun->id, $student->tahun_akademik_id);
        $this->assertEquals($this->activeSemester->id, $student->semester_id);
    }

    /** @test */
    public function dpl_monitoring_filters_by_active_period()
    {
        $lokasi = Lokasi::create([
            'nama_desa' => 'Test Desa',
            'nama_kecamatan' => 'Test Kec',
            'nama_kabupaten' => 'Test Kab',
            'nama_provinsi' => 'Test Prov',
            'latitude' => 0,
            'longitude' => 0
        ]);

        // Active Group
        $activeGroup = Kelompok::create([
            'nama_kelompok' => 'My Active Group',
            'angkatan_id' => $this->angkatan,
            'tahun_akademik_id' => $this->activeTahun->id,
            'semester_id' => $this->activeSemester->id,
            'lokasi_id' => $lokasi->id,
            'dpl_id' => $this->dpl->id,
        ]);

        // Inactive Group
        $inactiveGroup = Kelompok::create([
            'nama_kelompok' => 'My Inactive Group',
            'angkatan_id' => $this->angkatan,
            'tahun_akademik_id' => $this->inactiveTahun->id,
            'semester_id' => $this->inactiveSemester->id,
            'lokasi_id' => $lokasi->id,
            'dpl_id' => $this->dpl->id,
        ]);

        $response = $this->actingAs($this->dpl)->get(route('groups.monitoring'));

        $response->assertStatus(200);
        $response->assertSee($activeGroup->nama_kelompok);
        $response->assertDontSee($inactiveGroup->nama_kelompok);
    }
}
