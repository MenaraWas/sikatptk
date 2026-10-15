<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Ptk;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));
        $status = trim($request->query('status', ''));
        $tahun = trim($request->query('tahun', ''));
        $ptkId = $request->query('ptk_id');

        $query = Kegiatan::with(['ptk', 'submission'])
            ->orderBy('created_at', 'desc');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul_pelatihan', 'like', "%{$search}%")
                    ->orWhere('penyelenggara', 'like', "%{$search}%")
                    ->orWhereHas('submission', function ($sub) use ($search) {
                        $sub->where('nama_pengisi', 'like', "%{$search}%")
                            ->orWhere('nip', 'like', "%{$search}%")
                            ->orWhere('batch_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($status !== '') {
            $query->where('status_verifikasi', $status);
        }

        if ($tahun !== '') {
            $query->where('tahun_pelaksanaan', $tahun);
        }

        if (!empty($ptkId)) {
            $query->where('ptk_id', $ptkId);
        }

        $kegiatans = $query->paginate(20)->withQueryString();

        $availableYears = Kegiatan::select('tahun_pelaksanaan')
            ->distinct()
            ->orderBy('tahun_pelaksanaan', 'desc')
            ->pluck('tahun_pelaksanaan')
            ->toArray();

        $ptks = Ptk::where('is_active', true)->orderBy('nama')->get(['id', 'nama']);
        $jumlahTerhapus = Kegiatan::onlyTrashed()->count();

        return view('admin.kegiatan.index', compact(
            'kegiatans',
            'jumlahTerhapus',
            'availableYears',
            'ptks',
            'search',
            'status',
            'tahun',
            'ptkId'
        ));
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load(['ptk', 'submission']);
        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    public function updateStatus(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:pending,terverifikasi,ditolak',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        $kegiatan->update([
            'status_verifikasi' => $request->input('status_verifikasi'),
            'catatan_verifikasi' => $request->input('catatan_verifikasi'),
            'verified_at' => $request->input('status_verifikasi') === 'pending' ? null : now(),
        ]);

        return back()->with('success', 'Status verifikasi kegiatan berhasil diperbarui.');
    }

    public function downloadFile(Kegiatan $kegiatan)
    {
        if (!$kegiatan->file_sertifikat || !Storage::disk('public')->exists($kegiatan->file_sertifikat)) {
            return back()->with('error', 'Berkas sertifikat tidak ditemukan di server.');
        }

        $path = Storage::disk('public')->path($kegiatan->file_sertifikat);
        $downloadName = $kegiatan->file_original_name ?: basename($kegiatan->file_sertifikat);

        return response()->download($path, $downloadName);
    }

    public function previewFile(Kegiatan $kegiatan)
    {
        if (!$kegiatan->file_sertifikat || !Storage::disk('public')->exists($kegiatan->file_sertifikat)) {
            abort(404, 'Berkas sertifikat tidak ditemukan.');
        }

        $path     = Storage::disk('public')->path($kegiatan->file_sertifikat);
        $mime     = $kegiatan->file_mime ?: mime_content_type($path);
        $content  = file_get_contents($path);

        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . ($kegiatan->file_original_name ?: basename($path)) . '"')
            ->header('Cache-Control', 'private, max-age=3600');
    }

    /**
     * Hapus satu kegiatan (soft delete). Berkas sertifikat TIDAK dihapus,
     * supaya data bisa dipulihkan dari menu Data Terhapus.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        DB::transaction(function () use ($kegiatan) {
            $submission = $kegiatan->submission;

            $kegiatan->delete();

            if ($submission) {
                $sisa = $submission->kegiatans()->count();

                if ($sisa === 0) {
                    // Pengiriman kosong ikut disembunyikan
                    $submission->delete();
                } else {
                    $submission->update(['jumlah_kegiatan' => $sisa]);
                }
            }
        });

        // Tidak memakai back(): kalau dihapus dari halaman detail, halaman itu sudah tidak ada.
        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan dipindahkan ke Data Terhapus dan masih bisa dipulihkan.');
    }

    /**
     * Hapus seluruh kegiatan dalam satu pengiriman (soft delete).
     */
    public function destroySubmission(Submission $submission)
    {
        $jumlah = 0;

        DB::transaction(function () use ($submission, &$jumlah) {
            foreach ($submission->kegiatans()->get() as $kegiatan) {
                $kegiatan->delete();
                $jumlah++;
            }

            $submission->delete();
        });

        return redirect()->route('admin.kegiatan.index')
            ->with('success', "Pengiriman {$submission->batch_code} ({$jumlah} kegiatan) dipindahkan ke Data Terhapus.");
    }

    /**
     * Daftar kegiatan yang sudah dihapus (soft delete).
     */
    public function trash()
    {
        $kegiatans = Kegiatan::onlyTrashed()
            ->with([
                'ptk',
                'submission' => fn ($q) => $q->withTrashed(),
            ])
            ->orderByDesc('deleted_at')
            ->paginate(20);

        return view('admin.kegiatan.trash', compact('kegiatans'));
    }

    /**
     * Pulihkan kegiatan yang terhapus.
     */
    public function restore(int $id)
    {
        $kegiatan = Kegiatan::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($kegiatan) {
            $submission = Submission::withTrashed()->find($kegiatan->submission_id);

            if ($submission && $submission->trashed()) {
                $submission->restore();
            }

            $kegiatan->restore();

            if ($submission) {
                $submission->update(['jumlah_kegiatan' => $submission->kegiatans()->count()]);
            }
        });

        return back()->with('success', 'Kegiatan berhasil dipulihkan.');
    }

    /**
     * Hapus permanen: data dan berkas sertifikat hilang dan tidak bisa dipulihkan.
     */
    public function forceDestroy(int $id)
    {
        $kegiatan = Kegiatan::onlyTrashed()->findOrFail($id);
        $file = $kegiatan->file_sertifikat;
        $submissionId = $kegiatan->submission_id;

        DB::transaction(function () use ($kegiatan, $submissionId) {
            $kegiatan->forceDelete();

            $submission = Submission::withTrashed()->find($submissionId);

            // Hapus pengiriman hanya kalau sudah tidak punya kegiatan sama sekali
            if ($submission && $submission->kegiatans()->withTrashed()->count() === 0) {
                $submission->forceDelete();
            }
        });

        if ($file && Storage::disk('public')->exists($file)) {
            Storage::disk('public')->delete($file);
        }

        return back()->with('success', 'Kegiatan dan berkas sertifikatnya dihapus permanen.');
    }

    /**
     * Export rekap data kegiatan ke CSV format (bisa langsung dibuka di Microsoft Excel).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $search = trim($request->query('q', ''));
        $status = trim($request->query('status', ''));
        $tahun = trim($request->query('tahun', ''));
        $ptkId = $request->query('ptk_id');

        $query = Kegiatan::with(['ptk', 'submission'])->orderBy('tanggal_pelaksanaan', 'desc');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul_pelatihan', 'like', "%{$search}%")
                    ->orWhere('penyelenggara', 'like', "%{$search}%")
                    ->orWhereHas('submission', function ($sub) use ($search) {
                        $sub->where('nama_pengisi', 'like', "%{$search}%")
                            ->orWhere('nip', 'like', "%{$search}%");
                    });
            });
        }

        if ($status !== '') {
            $query->where('status_verifikasi', $status);
        }

        if ($tahun !== '') {
            $query->where('tahun_pelaksanaan', $tahun);
        }

        if (!empty($ptkId)) {
            $query->where('ptk_id', $ptkId);
        }

        $fileName = 'rekap_kegiatan_ptk_' . date('Y-m-d_His') . '.csv';

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Tambahkan UTF-8 BOM agar terbaca sempurna di Microsoft Excel (Windows)
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No',
                'Kode Batch',
                'Nama PTK',
                'NIP',
                'Jenis Kelamin',
                'No HP',
                'Jenis Pelatihan',
                'Judul Pelatihan',
                'Penyelenggara',
                'Tanggal Pelaksanaan',
                'Tahun',
                'Jumlah JPL',
                'Status Verifikasi',
                'Catatan Verifikasi',
                'Tgl Upload'
            ]);

            $no = 1;
            $query->chunk(100, function ($rows) use ($handle, &$no) {
                foreach ($rows as $item) {
                    fputcsv($handle, [
                        $no++,
                        $item->submission?->batch_code ?? '-',
                        $item->submission?->nama_pengisi ?? $item->ptk?->nama ?? '-',
                        $item->submission?->nip ?? $item->ptk?->nip ?? '-',
                        $item->submission?->jenis_kelamin ?? $item->ptk?->jenis_kelamin ?? '-',
                        $item->submission?->no_hp ?? $item->ptk?->no_hp ?? '-',
                        $item->jenis_pelatihan,
                        $item->judul_pelatihan,
                        $item->penyelenggara,
                        $item->tanggal_pelaksanaan?->format('d/m/Y') ?? '-',
                        $item->tahun_pelaksanaan,
                        $item->jumlah_jpl,
                        ucfirst($item->status_verifikasi),
                        $item->catatan_verifikasi ?? '',
                        $item->created_at?->format('d/m/Y H:i') ?? '-'
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}