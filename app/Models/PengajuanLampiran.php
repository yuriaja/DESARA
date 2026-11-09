<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PengajuanLampiran extends Model {protected $guarded=[];public function pengajuan(){return $this->belongsTo(Pengajuan::class);}}
