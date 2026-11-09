<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LayananKategori extends Model {protected $guarded=[];public function layanan(){return $this->hasMany(LayananDesa::class);}}
