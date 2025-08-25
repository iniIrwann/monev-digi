<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use App\Models\Realisasi;
use App\Models\Target;
use App\Models\Capaian;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function indexKec()
    {
        return view('page.kecamatan.dashboard.index');
    }

    public function index()
    {
        $userId = auth()->id();

        // Hitung rata-rata capaian fisik
        $rataFisik = Capaian::where('user_id', $userId)
            ->avg('persen_capaian_keluaran');

        // Hitung rata-rata capaian keuangan
        $rataKeuangan = Capaian::where('user_id', $userId)
            ->avg('persen_capaian_keuangan');

        $hasil = [
            'rata_fisik' => round($rataFisik, 2),
            'rata_keuangan' => round($rataKeuangan, 2),
        ];

        // Aggregate Realisasi data by target_id, bidang_id, kegiatan_id, sub_kegiatan_id
        $realisasiAggregated = Realisasi::where('user_id', $userId)
            ->select(
                'target_id',
                DB::raw('SUM(realisasi_keuangan) as total_realisasi_keuangan'),
                DB::raw('SUM(volume_keluaran) as total_volume_keluaran'),
                DB::raw('COUNT(*) as tahap_count')
            )
            ->groupBy('target_id', 'bidang_id', 'kegiatan_id', 'sub_kegiatan_id')
            ->get();

        // Jumlah realisasi yang sudah dan belum terpenuhi
        $jumlahTerpenuhi = $realisasiAggregated->where('total_realisasi_keuangan', '>', 0)->count();
        $jumlahBelumTerpenuhi = $realisasiAggregated->where('total_realisasi_keuangan', 0)->count();

        // Total target dan capaian milik user
        $totalTarget = Target::where('user_id', $userId)->count();
        $totalCapaian = Capaian::where('user_id', $userId)->count();

        // Capaian sempurna: kedua persentase >= 100
        $jumlahCapaianSempurna = Capaian::where('user_id', $userId)
            ->where('persen_capaian_keluaran', '>=', 100)
            ->where('persen_capaian_keuangan', '>=', 100)
            ->count();

        $capaianTercapai = $jumlahCapaianSempurna;

        // Ambil data jumlah target per tanggal
        $targetPerTanggal = Target::where('user_id', $userId)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('tanggal', 'ASC')
            ->get();

        $labels = $targetPerTanggal->pluck('tanggal')->toArray();
        $data = $targetPerTanggal->pluck('total')->toArray();

        // Kategori capaian keluaran
        $kategoriKeluaran = [
            'sangat_kurang' => Capaian::where('user_id', $userId)->where('persen_capaian_keluaran', '<', 40)->count(),
            'kurang' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keluaran', [40, 59.99])->count(),
            'cukup' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keluaran', [60, 74.99])->count(),
            'baik' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keluaran', [75, 89.99])->count(),
            'sangat_baik' => Capaian::where('user_id', $userId)->where('persen_capaian_keluaran', '>=', 90)->count(),
        ];

        // Kategori capaian keuangan
        $kategoriKeuangan = [
            'sangat_rendah' => Capaian::where('user_id', $userId)->where('persen_capaian_keuangan', '<', 40)->count(),
            'kurang' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keuangan', [40, 59.99])->count(),
            'cukup' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keuangan', [60, 74.99])->count(),
            'baik' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keuangan', [75, 89.99])->count(),
            'sangat_baik' => Capaian::where('user_id', $userId)->where('persen_capaian_keuangan', '>=', 90)->count(),
        ];

        // Jumlah realisasi yang semua fieldnya terpenuhi
        $jumlahRealisasiTerpenuhi = Realisasi::where('user_id', $userId)
            ->select(
                'target_id',
                DB::raw('COUNT(*) as total_rows'),
                DB::raw('SUM(CASE WHEN volume_keluaran IS NOT NULL AND volume_keluaran != 0 AND realisasi_keuangan IS NOT NULL AND realisasi_keuangan != 0 THEN 1 ELSE 0 END) as filled_count')
            )
            ->groupBy('target_id')
            ->havingRaw('total_rows = filled_count')
            ->count();

        // Hitung total capaian keuangan
        $totalPersenKeuangan = Capaian::where('user_id', $userId)->sum('persen_capaian_keuangan');
        $totalTargetKeuangan = $totalTarget; // sama dengan jumlah target
        $capaianKeuangan = $totalTargetKeuangan > 0 ? $totalPersenKeuangan / $totalTargetKeuangan : 0;

        // Hitung total persen capaian keuangan (penjumlahan semua baris)
        $totalPersenKeuangan = Capaian::where('user_id', $userId)->sum('persen_capaian_keuangan');

        // Hitung jumlah target (pembagi)
        $totalTargetKeuangan = Target::where('user_id', $userId)->count();

        // Hasil capaian keuangan
        $capaianKeuangan = $totalTargetKeuangan > 0
            ? $totalPersenKeuangan / $totalTargetKeuangan
            : 0;

        // Total anggaran (hijau)
        $totalAnggaran = Target::where('user_id', $userId)->sum('anggaran_target');

        // Total realisasi (pink)
        $totalRealisasi = Realisasi::where('user_id', $userId)->sum('realisasi_keuangan');

        $items = Bidang::where('bidangs.user_id', $userId)
            ->select(
                'bidangs.kode_rekening as kode_bidang',
                DB::raw('(SELECT COUNT(*) FROM targets WHERE targets.bidang_id = bidangs.id AND targets.user_id = ' . $userId . ') as jumlah_target_per_bidang'),
                DB::raw('(SELECT COALESCE(SUM(anggaran_target),0) FROM targets WHERE targets.bidang_id = bidangs.id AND targets.user_id = ' . $userId . ') as jumlah_target_anggaran_per_bidang'),
                DB::raw('(SELECT COALESCE(SUM(realisasi_keuangan),0) FROM realisasis WHERE realisasis.bidang_id = bidangs.id AND realisasis.user_id = ' . $userId . ') as jumlah_realisasi_keuangan_per_bidang')
            )
            ->get();


        $jumlah_target_anggaran_per_bidang = $items->sum('jumlah_target_anggaran_per_bidang');
        $jumlah_realisasi_keuangan_per_bidang = $items->sum('jumlah_realisasi_keuangan_per_bidang');

        // hitung total semua bidang
        $total_target = Target::where('user_id', $userId)
            ->count();

        $total_target_anggaran = Target::where('user_id', $userId)
            ->sum('anggaran_target');

        $total_realisasi_keuangan = Realisasi::where('user_id', $userId)
            ->sum('realisasi_keuangan');

        $total_target = Target::where('user_id', $userId)->count();

        return view('page.dashboard.dashboard', compact(
            // 'jumlahTerpenuhi',
            // 'jumlahBelumTerpenuhi',
            // 'totalTarget',
            // 'capaianTercapai',
            'jumlahCapaianSempurna',
            // 'totalCapaian',
            'labels',
            'items',
            'jumlah_target_anggaran_per_bidang',
            'jumlah_realisasi_keuangan_per_bidang',
            'total_target',
            'hasil',
            'data',
            'kategoriKeluaran',
            'kategoriKeuangan',
            'jumlahRealisasiTerpenuhi',
            'capaianKeuangan',
            'totalAnggaran',    // ⬅️ baru
            'totalRealisasi'
        ));
    }
}