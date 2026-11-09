<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function(Blueprint $t) { $t->string('role')->default('warga'); $t->string('nik',16)->nullable()->unique(); $t->text('alamat')->nullable(); $t->string('telepon',25)->nullable(); });
        Schema::create('layanan_kategoris', function(Blueprint $t){ $t->id();$t->string('nama')->unique();$t->text('deskripsi')->nullable();$t->timestamps();});
        Schema::create('layanan_desas', function(Blueprint $t){ $t->id();$t->foreignId('layanan_kategori_id')->constrained('layanan_kategoris');$t->string('nama')->unique();$t->text('deskripsi')->nullable();$t->string('status')->default('nonaktif');$t->json('form_schema');$t->json('persyaratan');$t->timestamps();});
        Schema::create('pengajuans', function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->foreignId('layanan_desa_id')->constrained('layanan_desas');$t->string('nomor_pengajuan')->unique();$t->string('status')->default('Diajukan');$t->json('data_pemohon');$t->json('jawaban');$t->text('catatan_petugas')->nullable();$t->string('hasil_path')->nullable();$t->timestamps();});
        Schema::create('pengajuan_lampirans', function(Blueprint $t){$t->id();$t->foreignId('pengajuan_id')->constrained('pengajuans')->cascadeOnDelete();$t->string('jenis');$t->string('nama_asli');$t->string('path');$t->timestamps();});
        Schema::create('pengajuan_riwayats', function(Blueprint $t){$t->id();$t->foreignId('pengajuan_id')->constrained('pengajuans')->cascadeOnDelete();$t->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();$t->string('status');$t->text('catatan')->nullable();$t->timestamps();});
    }
    public function down(): void {Schema::dropIfExists('pengajuan_riwayats');Schema::dropIfExists('pengajuan_lampirans');Schema::dropIfExists('pengajuans');Schema::dropIfExists('layanan_desas');Schema::dropIfExists('layanan_kategoris');Schema::table('users', fn(Blueprint $t)=>$t->dropColumn(['role','nik','alamat','telepon']));}
};