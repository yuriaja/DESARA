<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_surat_id',
        'nomor_surat',
        'nama_pemohon',
        'nik_pemohon',
        'tempat_lahir',
        'tanggal_lahir',
        'pekerjaan',
        'agama',
        'status_perkawinan',
        'jenis_kelamin',
        'alamat_pemohon',
        'data_tambahan',
        'tanggal_terbit',
        'file_path',
        'status_arsip',
    ];

    protected $casts = [
        'data_tambahan' => 'array',
        'tanggal_lahir' => 'date',
        'tanggal_terbit' => 'date',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}