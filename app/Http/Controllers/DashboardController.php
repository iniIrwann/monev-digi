<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Realisasi;
use App\Models\Target;
use App\Models\Capaian;
use Illuminate\Container\Attributes\DB;
// use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        // Jumlah realisasi yang sudah terpenuhi
        $totalRealisasiTepenuhi = Realisasi::whereNotNull('realisasi_keuangan')
            ->where('realisasi_keuangan', '!=', '')
            ->count();

        $jumlahTerpenuhi = $totalRealisasiTepenuhi;

        // Jumlah realisasi yang belum terpenuhi
        $jumlahBelumTerpenuhi = Realisasi::whereNull('realisasi_keuangan')
            ->orWhere('realisasi_keuangan', '')
            ->count();

        // Total target
        $totalTarget = Target::count();

        // ✅ Hitung total dan capaian sempurna dari tabel `capaian`
        $totalCapaian = Capaian::count();

        $jumlahCapaianSempurna = Capaian::where('persen_capaian_keluaran', '>=', 100)
            ->where('persen_capaian_keuangan', '>=', 100)
            ->count();

        // Hitung persentase capaian sempurna
        $capaianTercapai = $totalCapaian > 0
            ? round(($jumlahCapaianSempurna / $totalCapaian) * 100)
            : 0;

        // Ambil data jumlah target per tanggal (group by DATE(created_at))
        $targetPerTanggal = Target::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
        ->groupByRaw('DATE(created_at)')
        ->orderBy('tanggal', 'ASC')
        ->get();

        // Siapkan array untuk label dan data chart
        $labels = $targetPerTanggal->pluck('tanggal')->toArray();
        $data = $targetPerTanggal->pluck('total')->toArray();

            // --- Hitung distribusi kategori capaian keluaran ---
        $kategoriKeluaran = [
            'sangat_kurang' => Capaian::where('persen_capaian_keluaran', '<', 40)->count(),
            'kurang'        => Capaian::whereBetween('persen_capaian_keluaran', [40, 59.99])->count(),
            'cukup'         => Capaian::whereBetween('persen_capaian_keluaran', [60, 74.99])->count(),
            'baik'          => Capaian::whereBetween('persen_capaian_keluaran', [75, 89.99])->count(),
            'sangat_baik'   => Capaian::where('persen_capaian_keluaran', '>=', 90)->count(),
        ];

        // --- Hitung distribusi kategori capaian keuangan ---
        $kategoriKeuangan = [
            'sangat_rendah' => Capaian::where('persen_capaian_keuangan', '<', 40)->count(),
            'kurang'        => Capaian::whereBetween('persen_capaian_keuangan', [40, 59.99])->count(),
            'cukup'         => Capaian::whereBetween('persen_capaian_keuangan', [60, 74.99])->count(),
            'baik'          => Capaian::whereBetween('persen_capaian_keuangan', [75, 89.99])->count(),
            'sangat_baik'   => Capaian::where('persen_capaian_keuangan', '>=', 90)->count(),
        ];

        return view('page.dashboard.dashboard', compact(
            'totalRealisasiTepenuhi',
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