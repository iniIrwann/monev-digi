<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Capaian;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\SubKegiatan;
use App\Models\Target;
use Illuminate\Http\Request;

class RealisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $bidangId = $request->input('bidang');
        $search = $request->input('query');

        // Ambil semua bidang (untuk dropdown filter)
        $filterBidangs = Bidang::userOnly()->select('id', 'nama_bidang')->get();

        // Query utama
        $query = Bidang::userOnly()->with(['kegiatan.subkegiatan.realisasis']);

        // Filter Tahun
        if ($tahun) {
            $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }

        if ($bidangId) {
            $query->where('id', $bidangId);
        }

        // Filter pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_bidang', 'like', "%$search%")
                    ->orWhereHas('kegiatan', function ($q2) use ($search) {
                        $q2->where('nama_kegiatan', 'like', "%$search%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan', function ($q2) use ($search) {
                        $q2->where('nama_subkegiatan', 'like', "%$search%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan.realisasis', function ($q2) use ($search) {
                        $q2->where('uraian_keluaran', 'like', "%$search%");
                    });
            });
        }

        // Pagination
        $data = $query->paginate(5)->appends($request->query());

        return view('page.realisasi.realisasi', compact('data', 'filterBidangs'));
    }
    public function createSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        // Validasi dan ambil data
        $bidang = Bidang::userOnly()->findOrFail($bidang_id);
        $kegiatan = Kegiatan::userOnly()->findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::userOnly()->findOrFail($subkegiatan_id);
        $realisasi = Realisasi::userOnly()->where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();
        // $uraian_keluaran = $realisasi ? $realisasi->uraian_keluaran : '';

        // Logic to show the form for creating a new sub
        return view('page.realisasi.create_sub_realisasi', compact('bidang', 'kegiatan', 'subKegiatan', 'realisasi'));
    }
    public function storeSub(Request $request)
    {
        // Validasi input
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'subkegiatan_id' => 'required|exists:sub_kegiatans,id',
            'uraian_keluaran' => 'required|string|max:255',
            'volume_keluaran' => 'required|numeric',
            'tenaga_kerja' => 'nullable|numeric',
            'upah' => 'nullable|numeric',
            'BLT' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'cara_pengadaan' => 'required|string|max:255',
            'tahun' => 'required|numeric',
            'realisasi_keuangan' => 'required|numeric',
            'durasi' => 'nullable|numeric',
            'KPM' => 'nullable|numeric',
        ]);

        // Cari entri realisasi berdasarkan kombinasi bidang, kegiatan, subkegiatan, dan tahun
        $realisasi = Realisasi::userOnly()->where('bidang_id', $request->bidang_id)
            ->where('kegiatan_id', $request->kegiatan_id)
            ->where('sub_kegiatan_id', operator: $request->subkegiatan_id)
            ->first();

        if (!$realisasi) {
            return redirect()->route('realisasi.index')->with('error', 'Data realisasi tidak ditemukan.');
        }

        // Update atau isi data
        $realisasi->user_id = auth()->id();
        $realisasi->volume_keluaran = $request->volume_keluaran;
        $realisasi->cara_pengadaan = $request->cara_pengadaan;
        $realisasi->realisasi_keuangan = $request->realisasi_keuangan;
        $realisasi->tenaga_kerja = $request->tenaga_kerja;
        $realisasi->durasi = $request->durasi;
        $realisasi->upah = $request->upah;
        $realisasi->KPM = $request->KPM;
        $realisasi->BLT = $request->BLT;
        $realisasi->tahun = $request->tahun;
        $realisasi->keterangan = $request->keterangan;
        $realisasi->save();

        $target = Target::userOnly()->where('bidang_id', $request->bidang_id)
            ->where('kegiatan_id', $request->kegiatan_id)
            ->where('sub_kegiatan_id', $request->subkegiatan_id)
            ->first();

        // $capaian = Capaian::userOnly()->where('realisasi_id', $realisasi->id)
        //     ->where('kegiatan_id', $target->id)
        //     ->first();

        $persenan_capaian_volume =
            $realisasi->volume_keluaran /
            $target->volume_keluaran;
        $persenan_capaian_keuangan =
            $realisasi->realisasi_keuangan /
            $target->anggaran_target;

        $sisa = $realisasi->realisasi_keuangan - $target->anggaran_target ;

        Capaian::userOnly()->updateOrCreate(
            [
                'target_id' => $target->id,
                'realisasi_id' => $realisasi->id,
                'user_id' => auth()->id(),
            ],
            [
                'persen_capaian_keluaran' => $persenan_capaian_volume * 100,
                'persen_capaian_keuangan' => $persenan_capaian_keuangan * 100,
                'sisa' => $sisa,
                ]
        );

        return redirect()->route('realisasi.index')->with('success', 'Data realisasi berhasil disimpan atau diperbarui.');
    }
    public function deleteSubKegiatan($id)
    {
        // Cari entri realisasi berdasarkan ID
        $realisasi = Realisasi::userOnly()->findOrFail($id);

        $realisasi->uraian_keluaran = null;
        $realisasi->uraian_keluaran = null;
        $realisasi->volume_keluaran = null;
        $realisasi->cara_pengadaan = null;
        $realisasi->realisasi_keuangan = null;
        $realisasi->tenaga_kerja = null;
        $realisasi->durasi = null;
        $realisasi->upah = null;
        $realisasi->KPM = null;
        $realisasi->BLT = null;
        $realisasi->tahun = null;
        $realisasi->keterangan = null;

        $realisasi->save();

        return redirect()->route('realisasi.index')->with('success', 'Data realisasi berhasil dihapus.');
    }
    public function detail($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        // Ambil data realisasi berdasarkan bidang, kegiatan, dan subkegiatan
        $realisasi = Realisasi::userOnly()->where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();

        if (!$realisasi) {
            abort(404, 'Realisasi not found');
        }

        // Ambil data terkait lainnya
        $bidang = Bidang::userOnly()->findOrFail($bidang_id);
        $kegiatan = Kegiatan::userOnly()->findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::userOnly()->findOrFail($subkegiatan_id);

        return view('page.realisasi.detail', compact('realisasi', 'bidang', 'kegiatan', 'subKegiatan'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
