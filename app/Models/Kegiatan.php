<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'ptk_id',
        'urutan',
        'jenis_pelatihan',
        'judul_pelatihan',
        'penyelenggara',
        'tanggal_pelaksanaan',
        'tahun_pelaksanaan',
        'jumlah_jpl',
        'file_sertifikat',
        'file_original_name',
        'file_size',
        'file_mime',
        'catatan',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_at',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
        'jumlah_jpl' => 'integer',
        'urutan' => 'integer',
        'verified_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function ptk(): BelongsTo
    {
        return $this->belongsTo(Ptk::class);
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_sertifikat);
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}
