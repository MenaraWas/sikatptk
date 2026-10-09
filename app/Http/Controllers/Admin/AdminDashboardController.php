<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Ptk;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalKegiatan = Kegiatan::count();
        $totalJpl = Kegiatan::sum('jumlah_jpl');
        $totalPtk = Ptk::where('is_active', true)->count();
        $totalSubmission = Submission::count();

        $kegiatanPending = Kegiatan::where('status_verifikasi', 'pending')->count();
        $kegiatanTerverifikasi = Kegiatan::where('status_verifikasi', 'terverifikasi')->count();
        $kegiatanDitolak = Kegiatan::where('status_verifikasi', 'ditolak')->count();

        // 10 Kegiatan Terbaru
        $recentKegiatans = Kegiatan::with(['ptk', 'submission'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Rekap per jenis pelatihan
        $rekapJenis = Kegiatan::select('jenis_pelatihan', DB::raw('count(*) as total'))
            ->groupBy('jenis_pelatihan')
            ->orderBy('total', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'totalKegiatan',
            'totalJpl',
            'totalPtk',
            'totalSubmission',
            'kegiatanPending',
            'kegiatanTerverifikasi',
            'kegiatanDitolak',
            'recentKegiatans',
            'rekapJenis'
        ));
    }
}
