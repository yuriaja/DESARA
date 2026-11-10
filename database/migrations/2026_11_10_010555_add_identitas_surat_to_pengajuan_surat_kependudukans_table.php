<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surat_kependudukans', function (Blueprint $table) {
            $table->string('nomor_surat', 100)->nullable();
            $table->string('nama_kepala_desa', 150)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surat_kependudukans', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_surat',
                'nama_kepala_desa'
            ]);
        });
    }
};