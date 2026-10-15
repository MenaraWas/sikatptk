<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'batch_code',
        'ptk_id',
        'nama_pengisi',
        'nip',
        'jenis_kelamin',
        'no_hp',
        'email',
        'jumlah_kegiatan',
        'catatan',
        'ip_address',
        'user_agent',
    ];

    public function ptk(): BelongsTo
    {
        return $this->belongsTo(Ptk::class);
    }

    public function kegiatans(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }
}