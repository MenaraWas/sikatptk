<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Ptk;
use Illuminate\Http\Request;

class PublicRekapController extends Controller
{
    /**
     * Tampilkan rekapitulasi data kegiatan PTK publik.
     */
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));
        $tahun = trim($request->query('tahun', ''));
        $jenis = trim($request->query('jenis', ''));
        $ptkId = $request->query('ptk_id');

        // Urutan: 'terbaru' (default, berdasarkan waktu upload) atau 'pelaksanaan'
        $urut = $request->query('urut', 'terbaru');
        if (! in_array($urut, ['terbaru', 'pelaksanaan'], true)) {
            $urut = 'terbaru';
        }

        $query = Kegiatan::with(['ptk', 'submission']);

        if ($urut === 'pelaksanaan') {
            $query->orderByDesc('tanggal_pelaksanaan')->orderByDesc('id');
        } else {
            // id sebagai pembeda kedua, karena satu pengiriman bisa berisi
            // beberapa kegiatan dengan created_at yang sama persis
            $query->orderByDesc('created_at')->orderByDesc('id');
        }

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

        if ($tahun !== '') {
            $query->where('tahun_pelaksanaan', $tahun);
        }

        if ($jenis !== '') {
            $query->where('jenis_pelatihan', $jenis);
        }

        if (! empty($ptkId)) {
            $query->where('ptk_id', $ptkId);
        }

        $kegiatans = $query->paginate(15)->withQueryString();

        // Statistik ringkas
        $totalKegiatan = Kegiatan::count();
        $totalJpl = Kegiatan::sum('jumlah_jpl');
        $totalGuruBerpartisipasi = Kegiatan::distinct('ptk_id')->count('ptk_id');

        $availableYears = Kegiatan::select('tahun_pelaksanaan')
            ->distinct()
            ->orderBy('tahun_pelaksanaan', 'desc')
            ->pluck('tahun_pelaksanaan')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [date('Y'), date('Y') - 1];
        }

        $ptkList = Ptk::where('is_active', true)->orderBy('nama')->get(['id', 'nama']);

        return view('rekap.index', compact(
            'kegiatans',
            'totalKegiatan',
            'totalJpl',
            'totalGuruBerpartisipasi',
            'availableYears',
            'ptkList',
            'search',
            'tahun',
            'jenis',
            'ptkId',
            'urut'
        ));
    }
}