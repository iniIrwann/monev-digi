<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Realisasi;
use App\Models\Target;
use App\Models\User; // untuk daftar desa (jika desa disimpan di users)
use Illuminate\Http\Request;

class CapaianKecamatanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $bidangId = $request->input('bidang');
        $desaId = $request->input('desa');      // <--- tambahan
        $search = $request->input('query');

        // Ambil semua bidang (untuk dropdown filter)
        $filterBidangs = Bidang::select('id', 'nama_bidang')->get();

        // Ambil daftar desa untuk dropdown "Pilih Desa"
        // Asumsi: desa disimpan di tabel users dengan role = 'desa'
        $selectDesa = User::where('role', 'desa')->select('id', 'desa')->get();

        // Query utama: ambil relasi lengkap
        $query = Bidang::with(['kegiatan.subkegiatan.realisasis.capaian.target']);

        // Filter berdasarkan tahun (hanya tampilkan bidang yang punya realisasi di tahun tersebut)
        if ($tahun) {
            $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }

        // Filter berdasarkan desa (asumsi bidang punya kolom user_id yang menunjuk ke desa)
        if ($desaId) {
            $query->where('user_id', $desaId);
        }

        // Filter berdasarkan bidang terpilih
        if ($bidangId) {
            $query->where('id', $bidangId);
        }

        // Filter pencarian (nama bidang / kegiatan / subkegiatan / uraian realisasi)
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

        // Pagination
        $data = $query->paginate(5)->appends($request->query());

        return view('page.kecamatan.capaian.capaian', compact('data', 'filterBidangs', 'selectDesa'));
    }

    public function detail($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $capaian = Bidang::with(['kegiatan.subkegiatan.realisasis.capaian.target'])
            ->where('id', $bidang_id)
            ->first();

        if (!$capaian) {
            abort(404, 'Capaian not found');
        }

        $realisasi = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();

        $target = Target::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = $bidang->kegiatan()->findOrFail($kegiatan_id);
        $subkegiatan = $kegiatan->subkegiatan()->findOrFail($subkegiatan_id);

        return view('page.kecamatan.capaian.detail', compact('capaian', 'bidang', 'kegiatan', 'subkegiatan', 'realisasi', 'target'));
    }
}
