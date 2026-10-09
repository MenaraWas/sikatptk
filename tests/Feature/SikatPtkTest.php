<?php

namespace Tests\Feature;

use App\Models\Ptk;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SikatPtkTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_open_form_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Upload Kegiatan');
        $response->assertSee('SIKAT PTK');
    }

    public function test_api_ptk_search_returns_json(): void
    {
        $ptk = Ptk::first();
        $this->assertNotNull($ptk);

        $response = $this->getJson('/api/ptk/search?q=' . urlencode(substr($ptk->nama, 0, 4)));
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $ptk->id,
            'nama' => $ptk->nama,
        ]);
    }

    public function test_can_submit_multiple_kegiatans_up_to_10(): void
    {
        Storage::fake('public');

        $ptk = Ptk::first();

        $file1 = UploadedFile::fake()->create('sertifikat_1.pdf', 500, 'application/pdf');
        $file2 = UploadedFile::fake()->create('sertifikat_2.pdf', 300, 'application/pdf');

        $payload = [
            'ptk_id' => $ptk->id,
            'nama_pengisi' => $ptk->nama,
            'nip' => $ptk->nip,
            'jenis_kelamin' => 'Laki-Laki',
            'no_hp' => '08123456789',
            'kegiatan' => [
                [
                    'jenis_pelatihan' => 'Online',
                    'judul_pelatihan' => 'Pelatihan AI untuk Pembelajaran Madrasah',
                    'penyelenggara' => 'Balai Diklat Keagamaan',
                    'tanggal_pelaksanaan' => '2026-05-10',
                    'tahun_pelaksanaan' => '2026',
                    'jumlah_jpl' => 32,
                    'file_sertifikat' => $file1,
                ],
                [
                    'jenis_pelatihan' => 'Workshop / Lokakarya',
                    'judul_pelatihan' => 'Workshop Penyusunan Kurikulum Merdeka',
                    'penyelenggara' => 'Kankemenag Bantul',
                    'tanggal_pelaksanaan' => '2026-06-15',
                    'tahun_pelaksanaan' => '2026',
                    'jumlah_jpl' => 16,
                    'file_sertifikat' => $file2,
                ],
            ],
            'catatan' => 'Berkas asli sudah diverifikasi secara mandiri',
        ];

        $response = $this->post('/upload', $payload);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        // Cek database
        $this->assertDatabaseHas('submissions', [
            'nama_pengisi' => $ptk->nama,
            'jumlah_kegiatan' => 2,
        ]);

        $submission = Submission::where('nama_pengisi', $ptk->nama)->latest()->first();
        $this->assertNotNull($submission);
        $this->assertCount(2, $submission->kegiatans);

        // Success page
        $successResponse = $this->get('/sukses/' . $submission->batch_code);
        $successResponse->assertStatus(200);
        $successResponse->assertSee($submission->batch_code);
        $successResponse->assertSee('Pelatihan AI untuk Pembelajaran Madrasah');
    }

    public function test_cannot_submit_more_than_10_kegiatans(): void
    {
        Storage::fake('public');

        $kegiatans = [];
        for ($i = 1; $i <= 11; $i++) {
            $kegiatans[] = [
                'jenis_pelatihan' => 'Online',
                'judul_pelatihan' => "Kegiatan ke-$i",
                'penyelenggara' => 'Penyelenggara Test',
                'tanggal_pelaksanaan' => '2026-01-01',
                'tahun_pelaksanaan' => '2026',
                'jumlah_jpl' => 10,
                'file_sertifikat' => UploadedFile::fake()->create("sertifikat_$i.pdf", 100, 'application/pdf'),
            ];
        }

        $payload = [
            'nama_pengisi' => 'Guru Penguji',
            'jenis_kelamin' => 'Perempuan',
            'kegiatan' => $kegiatans,
        ];

        $response = $this->post('/upload', $payload);
        $response->assertSessionHasErrors(['kegiatan']);
    }

    public function test_public_rekap_page_is_accessible(): void
    {
        $response = $this->get('/data-kegiatan');
        $response->assertStatus(200);
        $response->assertSee('Data Kegiatan');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Pengelola');
    }

    public function test_admin_can_export_kegiatan_csv(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/kegiatan/export-csv');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type'), 'text/csv'));
    }

    public function test_admin_can_verify_kegiatan(): void
    {
        Storage::fake('public');
        $admin = User::first();
        $ptk = Ptk::first();

        $file = UploadedFile::fake()->create('sertifikat.pdf', 200, 'application/pdf');

        $this->post('/upload', [
            'nama_pengisi' => $ptk->nama,
            'jenis_kelamin' => 'Laki-Laki',
            'kegiatan' => [
                [
                    'jenis_pelatihan' => 'Online',
                    'judul_pelatihan' => 'Uji Verifikasi',
                    'penyelenggara' => 'Kemenag',
                    'tanggal_pelaksanaan' => '2026-02-01',
                    'tahun_pelaksanaan' => '2026',
                    'jumlah_jpl' => 30,
                    'file_sertifikat' => $file,
                ]
            ]
        ]);

        $submission = Submission::latest()->first();
        $kegiatan = $submission->kegiatans->first();

        $response = $this->actingAs($admin)->post('/admin/kegiatan/' . $kegiatan->id . '/status', [
            'status_verifikasi' => 'terverifikasi',
            'catatan_verifikasi' => 'Disetujui lengkap.',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('kegiatans', [
            'id' => $kegiatan->id,
            'status_verifikasi' => 'terverifikasi',
            'catatan_verifikasi' => 'Disetujui lengkap.',
        ]);
    }
}
