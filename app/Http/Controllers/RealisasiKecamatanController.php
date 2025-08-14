<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Capaian;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\RealisasiTahap;
use App\Models\SubKegiatan;
use App\Models\Target;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Query: ambil bidang beserta kegiatan -> subkegiatan -> realisasis -> tahaps
        $query = Bidang::with(['kegiatan.subkegiatan.realisasis.tahaps']);

        if ($desaId) {
            $query->where('user_id', $desaId);
        }

        if (!empty($bidangId) && is_numeric($bidangId)) {
            $query->where('id', $bidangId);
            $bidang = Bidang::find($bidangId);
        }

        if (!empty($tahun) && is_numeric($tahun)) {
            $query->whereHas('kegiatan.subkegiatan.realisasis.tahaps', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }

        // Pencarian teks pada bidang/kegiatan/subkegiatan/uraian di tahaps
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_bidang', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($q2) use ($search) {
                        $q2->where('nama_kegiatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan', function ($q2) use ($search) {
                        $q2->where('nama_subkegiatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan.realisasis.tahaps', function ($q2) use ($search) {
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

        // Ambil realisasi jika sudah ada (beserta tahaps)
        $realisasi = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->with('tahaps')
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
            ->with('tahaps')
            ->first();

        return view('page.kecamatan.realisasi.edit_sub_realisasi', compact('bidang', 'kegiatan', 'subKegiatan', 'realisasi'));
    }

    // Simpan / update realisasi (create or update per tahap)
    public function storeSub(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'subkegiatan_id' => 'required|exists:sub_kegiatans,id',
            'volume_keluaran' => 'required|numeric',
            'tenaga_kerja' => 'nullable|numeric',
            'upah' => 'nullable|numeric',
            'BLT' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'realisasi_keuangan' => 'required|numeric',
            'durasi' => 'nullable|numeric',
            'KPM' => 'nullable|numeric',
            'tahap' => 'nullable|in:1,2', // optional, default 1
            'tahun' => 'nullable|numeric',
        ]);

        $tahap = $request->input('tahap', 1);

        $bidang = Bidang::findOrFail($request->bidang_id);

        DB::transaction(function () use ($request, $bidang, $tahap) {
            // Pastikan ada row realisasi (identitas), create jika belum
            $realisasi = Realisasi::updateOrCreate(
                [
                    'bidang_id' => $request->bidang_id,
                    'kegiatan_id' => $request->kegiatan_id,
                    'sub_kegiatan_id' => $request->subkegiatan_id,
                ],
                [
                    'user_id' => $bidang->user_id,
                    // simpan di realisasi hanya identitas; detail per-tahap disimpan di realisasi_tahaps
                ]
            );

            // Simpan/Update data di tabel realisasi_tahaps berdasarkan tahap
            $realisasiTahap = RealisasiTahap::updateOrCreate(
                [
                    'realisasi_id' => $realisasi->id,
                    'tahap' => $tahap,
                ],
                [
                    'volume_keluaran' => $request->volume_keluaran,
                    'tenaga_kerja' => $request->tenaga_kerja,
                    'upah' => $request->upah,
                    'BLT' => $request->BLT,
                    'KPM' => $request->KPM,
                    'durasi' => $request->durasi,
                    'realisasi_keuangan' => $request->realisasi_keuangan,
                    'keterangan' => $request->keterangan,
                    'tahun' => $request->input('tahun'),
                    'uraian_keluaran' => $request->input('uraian_keluaran'),
                    'cara_pengadaan' => $request->input('cara_pengadaan'),
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
                if ($target->volume_keluaran > 0 && $realisasiTahap->volume_keluaran !== null) {
                    $persen_volume = ($realisasiTahap->volume_keluaran / $target->volume_keluaran) * 100;
                }

                if ($target->anggaran_target > 0 && $realisasiTahap->realisasi_keuangan !== null) {
                    $persen_keuangan = ($realisasiTahap->realisasi_keuangan / $target->anggaran_target) * 100;
                    $sisa = $realisasiTahap->realisasi_keuangan - $target->anggaran_target;
                } else {
                    $sisa = $realisasiTahap->realisasi_keuangan ?? null;
                }
            }

            // Simpan atau update capaian (pasangkan dengan target_id jika ada)
            Capaian::updateOrCreate(
                [
                    'target_id' => $target->id ?? null,
                    'realisasi_id' => $realisasi->id,
                    'user_id' => $bidang->user_id,
                ],
                [
                    'persen_capaian_keluaran' => $persen_volume,
                    'persen_capaian_keuangan' => $persen_keuangan,
                    'sisa' => $sisa,
                ]
            );
        });

        return redirect()->route('kecamatan.realisasi.index')->with('success', 'Data realisasi berhasil disimpan atau diperbarui.');
    }

    // Mengosongkan data realisasi (bukan hapus baris) — sekarang untuk semua tahapan
    public function deleteSubKegiatan($id)
    {
        $realisasi = Realisasi::findOrFail($id);

        DB::transaction(function () use ($realisasi) {
            // kosongkan semua field di semua tahapan realisasi
            RealisasiTahap::where('realisasi_id', $realisasi->id)->update([
                'uraian_keluaran' => null,
                'volume_keluaran' => null,
                'cara_pengadaan' => null,
                'realisasi_keuangan' => null,
                'tenaga_kerja' => null,
                'durasi' => null,
                'upah' => null,
                'KPM' => null,
                'BLT' => null,
                'tahun' => null,
                'keterangan' => null,
            ]);

            // Update capaian terkait (kosongkan)
            $capaian = Capaian::where('realisasi_id', $realisasi->id)->first();
            if ($capaian) {
                $capaian->persen_capaian_keluaran = null;
                $capaian->persen_capaian_keuangan = null;
                $capaian->sisa = null;
                $capaian->save();
            }
        });

        return redirect()->route('kecamatan.realisasi.index')->with('success', 'Data realisasi berhasil dihapus.');
    }

    // Detail realisasi per subkegiatan (lihat)
    public function detailSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $realisasi = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->with('tahaps') // penting agar view bisa akses tiap tahap
            ->first();

        if (!$realisasi) {
            abort(404, 'Realisasi not found');
        }

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        return view('page.kecamatan.realisasi.detail', compact('realisasi', 'bidang', 'kegiatan', 'subKegiatan'));
    }

    public function createCatatan($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $realisasi = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->with('tahaps') // penting agar view bisa akses tiap tahap
            ->first();

        if (!$realisasi) {
            abort(404, 'Realisasi not found');
        }

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        return view('page.kecamatan.realisasi.create_catatan', compact('realisasi', 'bidang', 'kegiatan', 'subKegiatan'));
    }
}
