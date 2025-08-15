<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\SubKegiatan;
use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CapaianKecamatanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $bidangId = $request->input('bidang');
        $search = trim($request->input('query'));
        $desaId = $request->input('desa');

        // Initialize variables for dynamic description
        $desa = null;
        $bidang = null;
        if ($desaId) {
            $desa = User::where('role', 'desa')->where('id', $desaId)->first();
        }
        if ($bidangId) {
            $bidang = Bidang::find($bidangId);
        }

        // Ambil semua desa untuk dropdown filter
        $selectDesa = User::where('role', 'desa')->select('id', 'desa')->get();

        // Ambil bidang berdasarkan desa (jika dipilih)
        $filterBidangs = collect();
        if ($desaId) {
            $filterBidangs = Bidang::where('user_id', $desaId)
                ->select('id', 'nama_bidang')
                ->get();
        }

        // Query utama dengan eager loading
        $query = Bidang::with([
            'kegiatan.subkegiatan.realisasis',
            'kegiatan.subkegiatan.targets'
        ]);

        // Filter by desa (user_id)
        if ($desaId) {
            $query->where('user_id', $desaId);
        }

        // Filter by bidang
        if ($bidangId) {
            $query->where('id', $bidangId);
        }

        // Filter by tahun
        if ($tahun) {
            $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }

        // Filter by search query
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_bidang', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($q2) use ($search) {
                        $q2->where('nama_kegiatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan', function ($q2) use ($search) {
                        $q2->where('nama_subkegiatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan.realisasis', function ($q2) use ($search) {
                        $q2->where('uraian_keluaran', 'like', "%{$search}%");
                    });
            });
        }

        // Ambil data dengan pagination
        $data = $query->paginate(5)->appends($request->query());

        // Aggregate volume_keluaran for each subkegiatan
        foreach ($data as $bidang) {
            foreach ($bidang->kegiatan as $kegiatan) {
                foreach ($kegiatan->subkegiatan as $sub) {
                    $realisasiAggregated = Realisasi::where('bidang_id', $bidang->id)
                        ->where('kegiatan_id', $kegiatan->id)
                        ->where('sub_kegiatan_id', $sub->id)
                        ->select(
                            DB::raw('SUM(volume_keluaran) as total_volume_keluaran'),
                            DB::raw('SUM(realisasi_keuangan) as total_realisasi_keuangan')
                        )
                        ->first();

                    $sub->total_volume_keluaran = $realisasiAggregated->total_volume_keluaran ?? 0;
                    $sub->total_realisasi_keuangan = $realisasiAggregated->total_realisasi_keuangan ?? 0;

                    // Set target and capaian to the first Target object
                    $target = $sub->targets->first();
                    $sub->target = $target;
                    $sub->capaian = $target;
                }
            }
        }

        return view('page.kecamatan.capaian.capaian', compact(
            'data',
            'filterBidangs',
            'selectDesa',
            'desa',
            'bidang',
            'tahun',
            'search'
        ));
    }

    public function detail($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subkegiatan = SubKegiatan::with('targets')->findOrFail($subkegiatan_id);

        // Fetch target data
        $target = $subkegiatan->targets->first();

        // Fetch realisasi data for Tahap 1 and Tahap 2
        $tahap1Data = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->where('tahap', 1)
            ->first();

        $tahap2Data = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->where('tahap', 2)
            ->first();

        // Fetch capaian from the first target
        $capaian = $target;

        return view('page.kecamatan.capaian.detail', compact(
            'bidang',
            'kegiatan',
            'subkegiatan',
            'target',
            'tahap1Data',
            'tahap2Data',
            'capaian'
        ));
    }
}
