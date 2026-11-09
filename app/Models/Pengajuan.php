<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pengajuan extends Model {protected $guarded=[];protected $casts=['data_pemohon'=>'array','jawaban'=>'array'];public function layanan(){return $this->belongsTo(LayananDesa::class,'layanan_desa_id');}public function warga(){return $this->belongsTo(User::class,'user_id');}public function lampirans(){return $this->hasMany(PengajuanLampiran::class);}public function riwayats(){return $this->hasMany(PengajuanRiwayat::class)->latest();}}
