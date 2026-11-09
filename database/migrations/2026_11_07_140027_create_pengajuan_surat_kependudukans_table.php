
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surat_kependudukans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('jenis_surat');

            $table->json('data_pemohon');

            $table->json('data_surat');

            $table->json('dokumen')->nullable();

            $table->string('status')
                ->default('Diajukan');

            $table->text('catatan_petugas')
                ->nullable();

            $table->timestamps();

            $table->index(['jenis_surat', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surat_kependudukans');
    }
};