<?php

namespace Database\Seeders;

use App\Models\Ptk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PtkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('data/gtk_data.csv');

        if (!File::exists($csvPath)) {
            $this->command->error("File CSV tidak ditemukan: {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');
        $headers = fgetcsv($file);

        $inserted = 0;
        while (($row = fgetcsv($file)) !== false) {
            if (empty($row) || empty($row[0])) {
                continue;
            }

            $data = array_combine($headers, $row);

            Ptk::updateOrCreate(
                ['nama' => trim($data['nama'])],
                [
                    'nip' => !empty($data['nip']) ? trim($data['nip']) : null,
                    'nik' => !empty($data['nik']) ? trim($data['nik']) : null,
                    'nuptk' => !empty($data['nuptk']) ? trim($data['nuptk']) : null,
                    'status_kepegawaian' => !empty($data['status_kepegawaian']) ? trim($data['status_kepegawaian']) : null,
                    'jenis_kelamin' => !empty($data['jenis_kelamin']) ? trim($data['jenis_kelamin']) : null,
                    'no_hp' => !empty($data['no_hp']) ? trim($data['no_hp']) : null,
                    'email' => !empty($data['email']) ? trim($data['email']) : null,
                    'tugas' => !empty($data['tugas']) ? trim($data['tugas']) : null,
                    'mapel' => !empty($data['mapel']) ? trim($data['mapel']) : null,
                    'is_active' => true,
                ]
            );
            $inserted++;
        }

        fclose($file);
        $this->command->info("Berhasil mengimpor {$inserted} data PTK/GTK.");
    }
}
