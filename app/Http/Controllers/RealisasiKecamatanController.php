<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Capaian;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\SubKegiatan;
use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;

class RealisasiKecamatanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $bidangId = $request->input('bidang');
        $search = $request->input('query');
        $desaId = $request->input('desa');
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

        $filterBidangs = collect();
        if ($desaId) {
            $filterBidangs = Bidang::where('user_id', $desaId)
                ->select('id', 'nama_bidang')
                ->get();
        }

        // Query: ambil bidang beserta kegiatan -> subkegiatan -> realisasis
        $query = Bidang::with(['kegiatan.subkegiatan.realisasis']);

        if ($desaId) {
            $query->where('user_id', $desaId);
        }

        if (!empty($bidangId) && is_numeric($bidangId)) {
            $query->where('id', $bidangId);
            $bidang = Bidang::find($bidangId);
        }

        if (!empty($tahun) && is_numeric($tahun)) {
            $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }

        // Pencarian teks pada bidang/kegiatan/subkegiatan/realisasi
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

        // Pagination + keep query strings
        $data = $query->paginate(5)->appends($request->query());

        return view('page.kecamatan.realisasi.realisasi', compact(
            'data',
            'filterBidangs',
            'selectDesa',
            'bidang',
            'desa',
            'tahun'
        ));
    }

    // Tampilkan form untuk membuat / mengisi realisasi per subkegiatan
    public function createSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        // Ambil realisasi jika sudah ada (mis. untuk tahun tertentu akan dipilih di form)
        $realisasi = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();

        return view('page.kecamatan.realisasi.create_sub_realisasi', compact('bidang', 'kegiatan', 'subKegiatan', 'realisasi'));
    }

    // Tampilkan halaman edit realisasi (bisa reuse form create)
    public function editSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        $realisasi = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();

        return view('page.kecamatan.realisasi.edit_sub_realisasi', compact('bidang', 'kegiatan', 'subKegiatan', 'realisasi'));
    }

    // Simpan / update realisasi (create or update)
    public function storeSub(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'subkegiatan_id' => 'required|exists:sub_kegiatans,id',
            // 'uraian_keluaran' => 'required|string|max:255',
            'volume_keluaran' => 'required|numeric',
            'tenaga_kerja' => 'nullable|numeric',
            'upah' => 'nullable|numeric',
            'BLT' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            // 'cara_pengadaan' => 'required|string|max:255',
            // 'tahun' => 'required|numeric',
            'realisasi_keuangan' => 'required|numeric',
            'durasi' => 'nullable|numeric',
            'KPM' => 'nullable|numeric',
        ]);

        $bidang = Bidang::findOrFail($request->bidang_id);

        // updateOrCreate realisasi berdasarkan kombinasi bidang/kegiatan/subkegiatan/tahun
        $realisasi = Realisasi::updateOrCreate(
            [
                'bidang_id' => $request->bidang_id,
                'kegiatan_id' => $request->kegiatan_id,
                'sub_kegiatan_id' => $request->subkegiatan_id,
                // 'tahun' => $request->tahun,
            ],
            [
                'user_id' => $bidang->user_id,
                // 'uraian_keluaran' => $request->uraian_keluaran,
                'volume_keluaran' => $request->volume_keluaran,
                // 'cara_pengadaan' => $request->cara_pengadaan,
                'realisasi_keuangan' => $request->realisasi_keuangan,
                'tenaga_kerja' => $request->tenaga_kerja,
                'durasi' => $request->durasi,
                'upah' => $request->upah,
                'KPM' => $request->KPM,
                'BLT' => $request->BLT,
                'keterangan' => $request->keterangan,
            ]
        );

        // Cari target yang sesuai (jika ada) untuk menghitung capaian
        $target = Target::where('bidang_id', $request->bidang_id)
            ->where('kegiatan_id', $request->kegiatan_id)
            ->where('sub_kegiatan_id', $request->subkegiatan_id)
            ->first();

        // Hitung persen capaian dengan guard bagi nol
        $persen_volume = null;
        $persen_keuangan = null;
        $sisa = null;

        if ($target) {
            if ($target->volume_keluaran > 0) {
                $persen_volume = ($realisasi->volume_keluaran / $target->volume_keluaran) * 100;
            } else {
                $persen_volume = null;
            }

            if ($target->anggaran_target > 0) {
                $persen_keuangan = ($realisasi->realisasi_keuangan / $target->anggaran_target) * 100;
                $sisa = $realisasi->realisasi_keuangan - $target->anggaran_target;
            } else {
                $persen_keuangan = null;
                $sisa = $realisasi->realisasi_keuangan;
            }
        }

        // Simpan atau update capaian (jika target ada, pasangkan dengan target_id; jika tidak ada simpan tanpa target_id)
        Capaian::updateOrCreate(
            [
                'target_id' => $target->id,
                'realisasi_id' => $realisasi->id,
                'user_id' => $bidang->user_id,
            ],
            [
                'persen_capaian_keluaran' => $persen_volume,
                'persen_capaian_keuangan' => $persen_keuangan,
                'sisa' => $sisa,
            ]
        );

        return redirect()->route('kecamatan.realisasi.index')->with('success', 'Data realisasi berhasil disimpan atau diperbarui.');
    }

    // Mengosongkan data realisasi (bukan hapus baris)
    public function deleteSubKegiatan($id)
    {
        $realisasi = Realisasi::findOrFail($id);

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

        // Update capaian terkait (kosongkan atau hapus sesuai kebutuhan)
        $capaian = Capaian::where('realisasi_id', $realisasi->id)->first();
        if ($capaian) {
            $capaian->persen_capaian_keluaran = null;
            $capaian->persen_capaian_keuangan = null;
            $capaian->sisa = null;
            $capaian->save();
        }

        return redirect()->route('kecamatan.realisasi.index')->with('success', 'Data realisasi berhasil dihapus.');
    }

    // Detail realisasi per subkegiatan (lihat)
    public function detailSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $realisasi = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();

        if (!$realisasi) {
            abort(404, 'Realisasi not found');
        }

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        return view('page.kecamatan.realisasi.detail_sub_realisasi', compact('realisasi', 'bidang', 'kegiatan', 'subKegiatan'));
    }
}
