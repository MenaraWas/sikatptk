<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Ptk;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KegiatanSubmissionController extends Controller
{
    /**
     * Tampilkan form terbuka untuk pengisian kegiatan PTK.
     */
    public function create()
    {
        $ptks = Ptk::where('is_active', true)
            ->orderBy('nama', 'asc')
            ->get(['id', 'nama', 'nip', 'jenis_kelamin', 'no_hp', 'status_kepegawaian', 'email']);

        $jenisPelatihanList = [
            'Online',
            'Offline',
            'Hybrid (Blended)',
            'Diklat Teknis / Fungsional',
            'Bimbingan Teknis (Bimtek)',
            'Workshop / Lokakarya',
            'Seminar / Webinar',
            'Short Course',
            'Pelatihan Mandiri (MOOC/Pintar Kemenag/PMM)',
            'Lainnya',
        ];

        return view('submission.create', compact('ptks', 'jenisPelatihanList'));
    }

    /**
     * Endpoint API pencarian data PTK (untuk autocomplete/dropdown interaktif).
     */
    public function searchPtk(Request $request)
    {
        $q = trim($request->query('q', ''));
        
        $query = Ptk::where('is_active', true);
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('nip', 'like', "%{$q}%");
            });
        }

        $results = $query->orderBy('nama', 'asc')
            ->limit(20)
            ->get(['id', 'nama', 'nip', 'jenis_kelamin', 'no_hp', 'status_kepegawaian', 'email']);

        return response()->json($results);
    }

    /**
     * Simpan pengajuan kegiatan (maksimal 10 kegiatan dalam 1 kali upload).
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $rules = [
            'ptk_id' => 'nullable|exists:ptks,id',
            'nama_pengisi' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'no_hp' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:100',
            'catatan' => 'nullable|string|max:1000',
            'kegiatan' => 'required|array|min:1|max:10',
            'kegiatan.*.jenis_pelatihan' => 'required|string|max:100',
            'kegiatan.*.judul_pelatihan' => 'required|string|max:500',
            'kegiatan.*.penyelenggara' => 'required|string|max:255',
            'kegiatan.*.tanggal_pelaksanaan' => 'required|date',
            'kegiatan.*.tahun_pelaksanaan' => 'required|digits:4',
            'kegiatan.*.jumlah_jpl' => 'required|integer|min:1|max:1000',
            'kegiatan.*.catatan' => 'nullable|string|max:500',
            'kegiatan.*.file_sertifikat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB per berkas
        ];

        $messages = [
            'nama_pengisi.required' => 'Nama lengkap wajib diisi atau dipilih dari daftar PTK.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'kegiatan.required' => 'Minimal harus mengunggah 1 kegiatan.',
            'kegiatan.min' => 'Minimal harus mengunggah 1 kegiatan.',
            'kegiatan.max' => 'Maksimal hanya dapat mengunggah 10 kegiatan dalam 1 kali pengiriman.',
            'kegiatan.*.judul_pelatihan.required' => 'Judul kegiatan/pelatihan wajib diisi.',
            'kegiatan.*.penyelenggara.required' => 'Nama penyelenggara wajib diisi.',
            'kegiatan.*.tanggal_pelaksanaan.required' => 'Tanggal pelaksanaan wajib diisi.',
            'kegiatan.*.tahun_pelaksanaan.required' => 'Tahun pelaksanaan wajib 4 digit angka.',
            'kegiatan.*.jumlah_jpl.required' => 'Jumlah JPL wajib diisi berupa angka.',
            'kegiatan.*.jumlah_jpl.min' => 'Jumlah JPL minimal 1 jam.',
            'kegiatan.*.file_sertifikat.required' => 'Berkas bukti sertifikat wajib diunggah untuk setiap kegiatan.',
            'kegiatan.*.file_sertifikat.mimes' => 'Format berkas sertifikat harus berupa PDF, JPG, JPEG, atau PNG.',
            'kegiatan.*.file_sertifikat.max' => 'Ukuran berkas sertifikat maksimal 10 MB per file.',
        ];

        $validated = $request->validate($rules, $messages);

        // 2. Simpan dalam DB transaction
        $submission = DB::transaction(function () use ($request, $validated) {
            $batchCode = 'SIKAT-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $submission = Submission::create([
                'batch_code' => $batchCode,
                'ptk_id' => $validated['ptk_id'] ?? null,
                'nama_pengisi' => trim($validated['nama_pengisi']),
                'nip' => !empty($validated['nip']) ? trim($validated['nip']) : null,
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_hp' => !empty($validated['no_hp']) ? trim($validated['no_hp']) : null,
                'email' => !empty($validated['email']) ? trim($validated['email']) : null,
                'jumlah_kegiatan' => count($validated['kegiatan']),
                'catatan' => $validated['catatan'] ?? null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $kegiatanList = $request->input('kegiatan', []);
            $fileList = $request->file('kegiatan', []);

            $order = 1;
            foreach ($kegiatanList as $index => $item) {
                $file = $fileList[$index]['file_sertifikat'] ?? null;
                
                $filePath = '';
                $originalName = '';
                $fileSize = 0;
                $fileMime = '';

                if ($file && $file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $fileSize = $file->getSize();
                    $fileMime = $file->getClientMimeType();

                    $year = $item['tahun_pelaksanaan'] ?? date('Y');
                    $sanitizedName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
                    $extension = $file->getClientOriginalExtension();
                    $fileName = "kegiatan_{$order}_" . time() . "_{$sanitizedName}.{$extension}";

                    $filePath = $file->storeAs("sertifikat/{$year}/submission_{$submission->id}", $fileName, 'public');
                }

                Kegiatan::create([
                    'submission_id' => $submission->id,
                    'ptk_id' => $submission->ptk_id,
                    'urutan' => $order,
                    'jenis_pelatihan' => $item['jenis_pelatihan'],
                    'judul_pelatihan' => trim($item['judul_pelatihan']),
                    'penyelenggara' => trim($item['penyelenggara']),
                    'tanggal_pelaksanaan' => $item['tanggal_pelaksanaan'],
                    'tahun_pelaksanaan' => $item['tahun_pelaksanaan'],
                    'jumlah_jpl' => (int) $item['jumlah_jpl'],
                    'file_sertifikat' => $filePath,
                    'file_original_name' => $originalName,
                    'file_size' => $fileSize,
                    'file_mime' => $fileMime,
                    'catatan' => $item['catatan'] ?? null,
                    'status_verifikasi' => 'pending',
                ]);

                $order++;
            }

            return $submission;
        });

        return redirect()->route('submission.success', ['code' => $submission->batch_code])
            ->with('success', 'Kegiatan PTK berhasil disimpan!');
    }

    /**
     * Tampilkan halaman sukses pengiriman berkas.
     */
    public function success($code)
    {
        $submission = Submission::with(['kegiatans', 'ptk'])
            ->where('batch_code', $code)
            ->firstOrFail();

        return view('submission.success', compact('submission'));
    }
}
