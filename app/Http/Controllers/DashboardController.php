<?php

namespace App\Http\Controllers;

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
        $userId = auth()->id(); // Ambil ID user yang sedang login

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

        // Jumlah realisasi yang sudah terpenuhi (realisasi_keuangan > 0 after summing both tahap)
        $totalRealisasiTerpenuhi = $realisasiAggregated
            ->where('total_realisasi_keuangan', '>', 0)
            ->count();

        $jumlahTerpenuhi = $totalRealisasiTerpenuhi;

        // Jumlah realisasi yang belum terpenuhi (realisasi_keuangan = 0 or null after summing both tahap)
        $jumlahBelumTerpenuhi = $realisasiAggregated
            ->where('total_realisasi_keuangan', 0)
            ->count();

        // Total target milik user
        $totalTarget = Target::where('user_id', $userId)->count();

        // Total capaian milik user
        $totalCapaian = Capaian::where('user_id', $userId)->count();

        // Capaian sempurna milik user (aggregate realisasi per target)
        $jumlahCapaianSempurna = Capaian::where('user_id', $userId)
            ->where('persen_capaian_keluaran', '>=', 100)
            ->where('persen_capaian_keuangan', '>=', 100)
            ->count();

        // Hitung persentase capaian sempurna
        $capaianTercapai = $totalCapaian > 0
            ? round(($jumlahCapaianSempurna / $totalCapaian) * 100)
            : 0;

        // Ambil data jumlah target per tanggal milik user
        $targetPerTanggal = Target::where('user_id', $userId)
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('tanggal', 'ASC')
            ->get();

        $labels = $targetPerTanggal->pluck('tanggal')->toArray();
        $data = $targetPerTanggal->pluck('total')->toArray();

        // Kategori capaian keluaran milik user
        $kategoriKeluaran = [
            'sangat_kurang' => Capaian::where('user_id', $userId)->where('persen_capaian_keluaran', '<', 40)->count(),
            'kurang' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keluaran', [40, 59.99])->count(),
            'cukup' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keluaran', [60, 74.99])->count(),
            'baik' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keluaran', [75, 89.99])->count(),
            'sangat_baik' => Capaian::where('user_id', $userId)->where('persen_capaian_keluaran', '>=', 90)->count(),
        ];

        // Kategori capaian keuangan milik user
        $kategoriKeuangan = [
            'sangat_rendah' => Capaian::where('user_id', $userId)->where('persen_capaian_keuangan', '<', 40)->count(),
            'kurang' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keuangan', [40, 59.99])->count(),
            'cukup' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keuangan', [60, 74.99])->count(),
            'baik' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keuangan', [75, 89.99])->count(),
            'sangat_baik' => Capaian::where('user_id', $userId)->where('persen_capaian_keuangan', '>=', 90)->count(),
        ];

        $jumlahRealisasiTerpenuhi = Realisasi::where('user_id', $userId)
            ->select(
                'target_id',
                DB::raw('COUNT(*) as total_rows'),
                DB::raw('SUM(CASE WHEN volume_keluaran IS NOT NULL AND volume_keluaran != 0
                           AND realisasi_keuangan IS NOT NULL AND realisasi_keuangan != 0
                           THEN 1 ELSE 0 END) as filled_count')
            )
            ->groupBy('target_id')
            ->havingRaw('total_rows = filled_count')
            ->count();


        return view('page.dashboard.dashboard', compact(
            'totalRealisasiTerpenuhi',
            'jumlahRealisasiTerpenuhi',
            'jumlahTerpenuhi',
            'jumlahBelumTerpenuhi',
            'totalTarget',
            'capaianTercapai',
            'jumlahCapaianSempurna',
            'totalCapaian',
            'labels',
            'data',
            'kategoriKeluaran',
            'kategoriKeuangan'
        ));
    }
}
