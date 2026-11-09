
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pengajuan_surat_kependudukans')
            ->where('status', 'Diajukan')
            ->update([
                'status' => 'Dalam Antrian',
            ]);

        Schema::table(
            'pengajuan_surat_kependudukans',
            function (Blueprint $table) {
                $table->string('status')
                    ->default('Dalam Antrian')
                    ->change();
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'pengajuan_surat_kependudukans',
            function (Blueprint $table) {
                $table->string('status')
                    ->default('Diajukan')
                    ->change();
            }
        );
    }
};