<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Ptk;
use Illuminate\Http\Request;
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

        return view('admin.kegiatan.index', compact(
            'kegiatans',
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

    public function destroy(Kegiatan $kegiatan)
    {
        if ($kegiatan->file_sertifikat && Storage::disk('public')->exists($kegiatan->file_sertifikat)) {
            Storage::disk('public')->delete($kegiatan->file_sertifikat);
        }

        $kegiatan->delete();

        return back()->with('success', 'Data kegiatan berhasil dihapus.');
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
