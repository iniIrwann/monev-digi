<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bidang;
use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use App\Models\Target;
use App\Models\Realisasi;
use App\Models\Capaian;
use Illuminate\Support\Facades\DB;

class MonevSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Capaian::truncate();
        Realisasi::truncate();
        Target::truncate();
        SubKegiatan::truncate();
        Kegiatan::truncate();
        Bidang::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $kodeBidang = range('A', 'J'); // A sampai J

        foreach ($kodeBidang as $index => $kode) {
            // Buat Bidang
            $bidang = Bidang::firstOrCreate(
                ['kode_rekening' => $kode],
                [
                    'nama_bidang' => "Bidang $kode",
                    'keterangan' => "Keterangan bidang $kode"
                ]
            );

            // Buat Kegiatan
            $kegiatan = Kegiatan::firstOrCreate(
                [
                    'bidang_id' => $bidang->id,
                    'kode_rekening' => $index + 1,
                ],
                [
                    'nama_kegiatan' => "Kegiatan untuk $kode",
                    'kategori' => 'Pembangunan'
                ]
            );

            // Buat SubKegiatan
            $sub = SubKegiatan::firstOrCreate(
                [
                    'kegiatan_id' => $kegiatan->id,
                    'kode_rekening' => 'SK' . ($index + 1)
                ],
                [
                    'nama_subkegiatan' => "Sub Kegiatan $kode"
                ]
            );

            // Buat Target
            $target = Target::create([
                'bidang_id' => $bidang->id,
                'kegiatan_id' => $kegiatan->id,
                'sub_kegiatan_id' => $sub->id,
                'uraian_keluaran' => 'meter',
                'volume_keluaran' => 100 + $index * 10,
                'cara_pengadaan' => 'Swakelola',
                'anggaran_target' => 10000000 + $index * 1000000,
                'tenaga_kerja' => 2 + $index,
                'durasi' => 30,
                'upah' => 500000,
                'KPM' => 5 + $index,
                'BLT' => 2000000,
                'tahun' => 2025,
                'keterangan' => "Target pengerjaan untuk $kode"
            ]);

            // Buat Realisasi
            $realisasi = Realisasi::create([
                'bidang_id' => $bidang->id,
                'kegiatan_id' => $kegiatan->id,
                'sub_kegiatan_id' => $sub->id,
                'volume_keluaran' => 100 + $index * 5,
                'realisasi_keuangan' => 9000000 + $index * 800000,
                'tahun' => 2025,
            ]);

            // Hitung persentase capaian
            $persenan_capaian_volume = 0;
            $persenan_capaian_keuangan = 0;
            $sisa = $realisasi->realisasi_keuangan - $target->anggaran_target;

            if ($target->volume_keluaran > 0) {
                $persenan_capaian_volume = $realisasi->volume_keluaran / $target->volume_keluaran;
            }

            if ($target->anggaran_target > 0) {
                $persenan_capaian_keuangan = $realisasi->realisasi_keuangan / $target->anggaran_target;
            }

            // Simpan Capaian
            Capaian::updateOrCreate(
                [
                    'target_id' => $target->id,
                    'realisasi_id' => $realisasi->id,
                ],
                [
                    'persen_capaian_keluaran' => $persenan_capaian_volume * 100,
                    'persen_capaian_keuangan' => $persenan_capaian_keuangan * 100,
                    'sisa' => $sisa,
                ]
            );
        }

        echo "Dummy data A sampai J berhasil dibuat beserta capaian.\n";
    }
}
