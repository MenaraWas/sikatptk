<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ptk;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminPtkController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('q', ''));

        $query = Ptk::withCount('kegiatans')->orderBy('nama', 'asc');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('status_kepegawaian', 'like', "%{$search}%");
            });
        }

        $ptks = $query->paginate(25)->withQueryString();

        return view('admin.ptk.index', compact('ptks', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:50',
            'nuptk' => 'nullable|string|max:50',
            'status_kepegawaian' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
            'no_hp' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:100',
            'tugas' => 'nullable|string|max:100',
            'mapel' => 'nullable|string|max:100',
        ]);

        $validated['is_active'] = true;

        Ptk::create($validated);

        return back()->with('success', 'Data PTK berhasil ditambahkan.');
    }

    public function update(Request $request, Ptk $ptk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:50',
            'nuptk' => 'nullable|string|max:50',
            'status_kepegawaian' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:Laki-Laki,Perempuan',
            'no_hp' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:100',
            'tugas' => 'nullable|string|max:100',
            'mapel' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $ptk->update($validated);

        return back()->with('success', 'Data PTK berhasil diperbarui.');
    }

    public function destroy(Ptk $ptk)
    {
        $ptk->delete();
        return back()->with('success', 'Data PTK berhasil dihapus.');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        $headers = fgetcsv($handle);
        if (!$headers) {
            return back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        // Normalisasi nama kolom header (lowercase, trim)
        $cleanHeaders = array_map(function ($h) {
            return strtolower(trim(str_replace([' ', '_'], '', $h)));
        }, $headers);

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row) || empty($row[0])) {
                continue;
            }

            $rowData = [];
            foreach ($cleanHeaders as $idx => $hName) {
                $rowData[$hName] = isset($row[$idx]) ? trim($row[$idx]) : '';
            }

            // Temukan kolom nama
            $nama = $rowData['nama'] ?? $rowData['namalengkap'] ?? '';
            if (empty($nama)) {
                continue;
            }

            $nip = $rowData['nip'] ?? null;
            $nik = $rowData['nik'] ?? null;
            $nuptk = $rowData['nuptk'] ?? null;
            $status = $rowData['statuskepegawaian'] ?? $rowData['status'] ?? null;
            $jk = $rowData['jeniskelamin'] ?? $rowData['jk'] ?? null;
            $noHp = $rowData['nohp'] ?? $rowData['nomorhandphone'] ?? $rowData['telepon'] ?? null;
            $email = $rowData['email'] ?? null;

            if ($jk) {
                if (stripos($jk, 'laki') !== false) {
                    $jk = 'Laki-Laki';
                } elseif (stripos($jk, 'peremp') !== false) {
                    $jk = 'Perempuan';
                }
            }

            Ptk::updateOrCreate(
                ['nama' => $nama],
                [
                    'nip' => $nip ?: null,
                    'nik' => $nik ?: null,
                    'nuptk' => $nuptk ?: null,
                    'status_kepegawaian' => $status ?: null,
                    'jenis_kelamin' => $jk ?: null,
                    'no_hp' => $noHp ?: null,
                    'email' => $email ?: null,
                    'is_active' => true,
                ]
            );

            $imported++;
        }

        fclose($handle);

        return back()->with('success', "Berhasil mengimpor/memperbarui {$imported} data PTK dari file CSV.");
    }
}
