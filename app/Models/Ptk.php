<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ptk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'nik',
        'nuptk',
        'status_kepegawaian',
        'jenis_kelamin',
        'no_hp',
        'email',
        'tugas',
        'mapel',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function kegiatans(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }
}
