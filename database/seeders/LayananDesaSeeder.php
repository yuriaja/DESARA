<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananDesaSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [["Administrasi Kependudukan", ["Surat Keterangan Domisili", "Surat Keterangan Penduduk", "Surat Keterangan Pindah Domisili", "Surat Keterangan Datang Penduduk", "Surat Keterangan Beda Nama / Identitas", "Surat Keterangan Belum Memiliki KTP", "Surat Pengantar Pembuatan KTP", "Surat Pengantar Pembuatan KK", "Surat Pengantar Perubahan Data KK", "Surat Keterangan atau Pengantar Kehilangan Dokumen"]], ["Administrasi Kelahiran dan Kematian", ["Surat Keterangan Kelahiran", "Surat Keterangan Kematian", "Surat Keterangan Lahir Mati", "Surat Pengantar Pengurusan Akta Kelahiran", "Surat Pengantar Pengurusan Akta Kematian", "Surat Keterangan Ahli Waris", "Surat Keterangan Hubungan Keluarga"]], ["Administrasi Pernikahan", ["Surat Pengantar Nikah", "Surat Keterangan Belum Menikah", "Surat Keterangan Status Perkawinan", "Surat Keterangan Janda / Duda", "Surat Keterangan Wali Nikah", "Surat Keterangan Asal-Usul", "Surat Keterangan Persetujuan Orang Tua"]], ["Administrasi Sosial dan Pendidikan", ["Surat Keterangan Tidak Mampu (SKTM)", "Surat Keterangan Penghasilan Orang Tua", "Surat Keterangan Penghasilan", "Surat Keterangan Belum Bekerja", "Surat Keterangan Tidak Memiliki Rumah", "Surat Keterangan untuk Pengajuan Beasiswa", "Surat Pengantar Pengajuan Bantuan Sosial", "Surat Keterangan Tanggungan Keluarga"]], ["Administrasi Usaha dan Pekerjaan", ["Surat Keterangan Usaha (SKU)", "Surat Keterangan Domisili Usaha", "Surat Keterangan Domisili Perusahaan", "Surat Keterangan Domisili Organisasi", "Surat Rekomendasi Usaha", "Surat Keterangan Tempat Usaha"]], ["Administrasi Pertanahan dan Bangunan", ["Surat Keterangan Riwayat Tanah", "Surat Keterangan Penguasaan Fisik Tanah", "Surat Keterangan Tanah Tidak Sengketa", "Surat Keterangan Kepemilikan Rumah", "Surat Pengantar Pengurusan Dokumen Pertanahan", "Surat Keterangan Batas Tanah"]], ["Administrasi Umum dan Keamanan", ["Surat Pengantar SKCK", "Surat Pengantar Izin Keramaian", "Surat Keterangan atau Pengantar Kehilangan Barang", "Surat Keterangan Bepergian", "Surat Rekomendasi Kegiatan Masyarakat", "Surat Keterangan Lainnya"]]];
        $fields = [
            'Surat Keterangan Domisili' => [['key'=>'alamat_domisili','label'=>'Alamat domisili saat ini','type'=>'textarea','required'=>true],['key'=>'alamat_asal','label'=>'Alamat asal','type'=>'textarea','required'=>true],['key'=>'tanggal_mulai_tinggal','label'=>'Tanggal mulai tinggal','type'=>'date','required'=>true],['key'=>'status_tempat_tinggal','label'=>'Status tempat tinggal','type'=>'select:Sendiri|Keluarga|Sewa|Lainnya','required'=>true]],
            'Surat Keterangan Kelahiran' => [['key'=>'nama_anak','label'=>'Nama anak','type'=>'text','required'=>true],['key'=>'jenis_kelamin_anak','label'=>'Jenis kelamin anak','type'=>'select:Laki-laki|Perempuan','required'=>true],['key'=>'tempat_lahir_anak','label'=>'Tempat lahir anak','type'=>'text','required'=>true],['key'=>'tanggal_lahir_anak','label'=>'Tanggal lahir anak','type'=>'date','required'=>true],['key'=>'nama_ayah','label'=>'Nama ayah','type'=>'text','required'=>true],['key'=>'nama_ibu','label'=>'Nama ibu','type'=>'text','required'=>true]],
            'Surat Keterangan Kematian' => [['key'=>'nama_almarhum','label'=>'Nama almarhum/almarhumah','type'=>'text','required'=>true],['key'=>'tanggal_kematian','label'=>'Tanggal kematian','type'=>'date','required'=>true],['key'=>'tempat_kematian','label'=>'Tempat kematian','type'=>'text','required'=>true],['key'=>'hubungan','label'=>'Hubungan pemohon dengan almarhum','type'=>'text','required'=>true]],
            'Surat Pengantar Nikah' => [['key'=>'nama_pasangan','label'=>'Nama calon pasangan','type'=>'text','required'=>true],['key'=>'alamat_pasangan','label'=>'Alamat calon pasangan','type'=>'textarea','required'=>true],['key'=>'status_perkawinan','label'=>'Status perkawinan','type'=>'select:Belum Kawin|Cerai Hidup|Cerai Mati','required'=>true],['key'=>'lokasi_nikah','label'=>'Rencana lokasi pernikahan','type'=>'text','required'=>true]],
            'Surat Keterangan Tidak Mampu (SKTM)' => [['key'=>'pekerjaan','label'=>'Pekerjaan','type'=>'text','required'=>true],['key'=>'penghasilan','label'=>'Perkiraan penghasilan keluarga (Rp)','type'=>'number','required'=>true],['key'=>'tanggungan','label'=>'Jumlah tanggungan','type'=>'number','required'=>true],['key'=>'tujuan_sktm','label'=>'Tujuan penggunaan SKTM','type'=>'textarea','required'=>true]],
            'Surat Keterangan Usaha (SKU)' => [['key'=>'nama_usaha','label'=>'Nama usaha','type'=>'text','required'=>true],['key'=>'bidang_usaha','label'=>'Jenis/bidang usaha','type'=>'text','required'=>true],['key'=>'alamat_usaha','label'=>'Alamat usaha','type'=>'textarea','required'=>true],['key'=>'tahun_mulai','label'=>'Tahun mulai usaha','type'=>'number','required'=>true],['key'=>'status_usaha','label'=>'Status kepemilikan usaha','type'=>'select:Milik sendiri|Bersama|Lainnya','required'=>true]],
            'Surat Pengantar SKCK' => [['key'=>'keperluan_skck','label'=>'Keperluan pengantar SKCK','type'=>'textarea','required'=>true]],
        ];
        foreach ($catalog as [$categoryName, $services]) {
            $categoryId = DB::table('layanan_kategoris')->insertOrIgnore(
                ['nama' => $categoryName], ['deskripsi' => 'Layanan administrasi '.$categoryName, 'updated_at' => now(), 'created_at' => now()]
            );
            $id = DB::table('layanan_kategoris')->where('nama', $categoryName)->value('id');
            foreach ($services as $serviceName) {
                $active = array_key_exists($serviceName, $fields);
                DB::table('layanan_desas')->insertOrIgnore(
                    ['nama' => $serviceName],
                    ['layanan_kategori_id'=>$id,'deskripsi'=>'Permohonan '.$serviceName,
                     'status'=>$active?'aktif':'nonaktif','form_schema'=>json_encode($fields[$serviceName] ?? []),
                     'persyaratan'=>json_encode([]),'updated_at'=>now(),'created_at'=>now()]
                );
            }
        }
    }
}
