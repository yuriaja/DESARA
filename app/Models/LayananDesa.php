<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LayananDesa extends Model {protected $guarded=[];protected $casts=['form_schema'=>'array','persyaratan'=>'array'];public function kategori(){return $this->belongsTo(LayananKategori::class,'layanan_kategori_id');}}
