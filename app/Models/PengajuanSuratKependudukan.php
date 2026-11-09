<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanSuratKependudukan extends Model
{
    protected $table = 'pengajuan_surat_kependudukans';

    protected $fillable = [
        'user_id',
        'jenis_surat',
        'data_pemohon',
        'data_surat',
        'dokumen',
        'status',
        'catatan_petugas',
    ];

    protected function casts(): array
    {
        return [
            'data_pemohon' => 'array',
            'data_surat' => 'array',
            'dokumen' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}