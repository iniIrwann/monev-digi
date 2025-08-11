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
    public function indexKec()
    {
        return view('page.kecamatan.dashboard.index');
    }
    public function index()
    {
        $userId = auth()->id(); // Ambil ID user yang sedang login

        // Jumlah realisasi yang sudah terpenuhi milik user
        $totalRealisasiTepenuhi = Realisasi::where('user_id', $userId)
            ->whereNotNull('realisasi_keuangan')
            ->where('realisasi_keuangan', '!=', '')
            ->count();

        $jumlahTerpenuhi = $totalRealisasiTepenuhi;

        // Jumlah realisasi yang belum terpenuhi milik user
        $jumlahBelumTerpenuhi = Realisasi::where('user_id', $userId)
            ->where(function ($q) {
                $q->whereNull('realisasi_keuangan')
                    ->orWhere('realisasi_keuangan', '');
            })
            ->count();

        // Total target milik user
        $totalTarget = Target::where('user_id', $userId)->count();

        // Total capaian milik user
        $totalCapaian = Capaian::where('user_id', $userId)->count();

        // Capaian sempurna milik user
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

        // --- Kategori capaian keluaran milik user ---
        $kategoriKeluaran = [
            'sangat_kurang' => Capaian::where('user_id', $userId)->where('persen_capaian_keluaran', '<', 40)->count(),
            'kurang' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keluaran', [40, 59.99])->count(),
            'cukup' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keluaran', [60, 74.99])->count(),
            'baik' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keluaran', [75, 89.99])->count(),
            'sangat_baik' => Capaian::where('user_id', $userId)->where('persen_capaian_keluaran', '>=', 90)->count(),
        ];

        // --- Kategori capaian keuangan milik user ---
        $kategoriKeuangan = [
            'sangat_rendah' => Capaian::where('user_id', $userId)->where('persen_capaian_keuangan', '<', 40)->count(),
            'kurang' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keuangan', [40, 59.99])->count(),
            'cukup' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keuangan', [60, 74.99])->count(),
            'baik' => Capaian::where('user_id', $userId)->whereBetween('persen_capaian_keuangan', [75, 89.99])->count(),
            'sangat_baik' => Capaian::where('user_id', $userId)->where('persen_capaian_keuangan', '>=', 90)->count(),
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
