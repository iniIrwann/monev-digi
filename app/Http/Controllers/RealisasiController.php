<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Capaian;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\SubKegiatan;
use App\Models\Target;
use DB;
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
        $search = trim($request->input('query'));
        $tahap = $request->input('tahap', 'all');

        // Data untuk dropdown filter bidang
        $filterBidangs = Bidang::userOnly()
            ->select('id', 'nama_bidang')
            ->get();

        // Base query dengan eager loading
        $query = Bidang::userOnly()->with([
            'kegiatan.subkegiatan.realisasis' => function ($q) use ($tahap) {
                if (in_array($tahap, ['1', '2'])) {
                    $q->where('tahap', (int) $tahap);
                }
            },
            'kegiatan.subkegiatan.targets' // Muat relasi targets tanpa filter tahap
        ]);

        // Filter tahun
        if ($tahun) {
            $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahun, $tahap) {
                $q->where('tahun', $tahun);
                if (in_array($tahap, ['1', '2'])) {
                    $q->where('tahap', (int) $tahap);
                }
            });
        }

        // Filter bidang
        if ($bidangId) {
            $query->where('id', $bidangId);
        }

        // Pencarian
        if (!empty($search)) {
            $query->where(function ($q) use ($search, $tahap) {
                $q->where('nama_bidang', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($q2) use ($search) {
                        $q2->where('nama_kegiatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan', function ($q2) use ($search) {
                        $q2->where('nama_subkegiatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan.realisasis', function ($q3) use ($search, $tahap) {
                        $q3->where('uraian_keluaran', 'like', "%{$search}%");
                        if (in_array($tahap, ['1', '2'])) {
                            $q3->where('tahap', (int) $tahap);
                        }
                    });
            });
        }

        // Ambil data dengan pagination
        $data = $query->paginate(10)->appends($request->query());

        // Siapkan data tambahan untuk view
        foreach ($data as $bidang) {
            foreach ($bidang->kegiatan as $kegiatan) {
                foreach ($kegiatan->subkegiatan as $sub) {
                    $sub->targetData = $sub->targets->first();
                    if ($tahap == 'all') {
                        $sub->tahap1Data = $sub->realisasis->where('tahap', '1')->first();
                        $sub->tahap2Data = $sub->realisasis->where('tahap', '2')->first();
                        $sub->persenKeuangan1 = $sub->tahap1Data && $sub->targetData && $sub->targetData->anggaran_target > 0
                            ? ($sub->tahap1Data->realisasi_keuangan / $sub->targetData->anggaran_target * 100)
                            : 0;
                        $sub->persenKeuangan2 = $sub->tahap2Data && $sub->targetData && $sub->targetData->anggaran_target > 0
                            ? ($sub->tahap2Data->realisasi_keuangan / $sub->targetData->anggaran_target * 100)
                            : 0;
                        // Perhitungan persen volume fisik total
                        $totalVolumeRealisasi = ($sub->tahap1Data?->volume_keluaran ?? 0) + ($sub->tahap2Data?->volume_keluaran ?? 0);
                        $sub->persenVolumeFisikTotal = $sub->targetData && $sub->targetData->volume_keluaran > 0
                            ? ($totalVolumeRealisasi / $sub->targetData->volume_keluaran * 100)
                            : 0;
                        $totalKeuanganRealisasi = ($sub->tahap1Data?->realisasi_keuangan ?? 0) + ($sub->tahap2Data?->realisasi_keuangan ?? 0);
                        $sub->persenVolumeKuanganTotal = $sub->targetData && $sub->targetData->anggaran_target > 0
                            ? ($totalKeuanganRealisasi / $sub->targetData->anggaran_target * 100)
                            : 0;
                    } else {
                        $sub->tahapData = $sub->realisasis->where('tahap', $tahap)->first();
                        $sub->persenKeuangan = $sub->tahapData && $sub->targetData && $sub->targetData->anggaran_target > 0
                            ? ($sub->tahapData->realisasi_keuangan / $sub->targetData->anggaran_target * 100)
                            : 0;
                        $sub->persenVolumeFisik = $sub->tahapData && $sub->targetData && $sub->targetData->volume_keluaran > 0
                            ? ($sub->tahapData->volume_keluaran / $sub->targetData->volume_keluaran * 100)
                            : 0;
                    }
                }
            }
        }

        return view('page.realisasi.realisasi', compact(
            'data',
            'filterBidangs',
            'tahap',
            'tahun',
            'bidangId',
            'search'
        ));
    }

    //  public function tahapdua(Request $request)
    // {
    //     $tahun = $request->input('tahun');
    //     $bidangId = $request->input('bidang');
    //     $search = $request->input('query');

    //     // Ambil semua bidang (untuk dropdown filter)
    //     $filterBidangs = Bidang::userOnly()->select('id', 'nama_bidang')->get();

    //     // Query utama
    //     $query = Bidang::userOnly()->with(['kegiatan.subkegiatan.realisasis']);

    //     // Filter Tahun
    //     if ($tahun) {
    //         $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahun) {
    //             $q->where('tahun', $tahun);
    //         });
    //     }

    //     if ($bidangId) {
    //         $query->where('id', $bidangId);
    //     }

    //     // Filter pencarian
    //     if (!empty($search)) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('nama_bidang', 'like', "%$search%")
    //                 ->orWhereHas('kegiatan', function ($q2) use ($search) {
    //                     $q2->where('nama_kegiatan', 'like', "%$search%");
    //                 })
    //                 ->orWhereHas('kegiatan.subkegiatan', function ($q2) use ($search) {
    //                     $q2->where('nama_subkegiatan', 'like', "%$search%");
    //                 })
    //                 ->orWhereHas('kegiatan.subkegiatan.realisasis', function ($q2) use ($search) {
    //                     $q2->where('uraian_keluaran', 'like', "%$search%");
    //                 });
    //         });
    //     }

    //     // Pagination
    //     $data = $query->paginate(5)->appends($request->query());

    //     return view('page.realisasi.realisasi_tahap2', compact('data', 'filterBidangs'));
    // }

    // public function total()
    // {
    //     // Contoh hitung total
    //     // $total = Realisasi::sum('jumlah');

    //     return view('page.realisasi.total_realisasi');
    // }

    public function createSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $tahap = request()->query('tahap', 1);
        // Validasi tahap
        if (!empty($tahap) && !in_array($tahap, ['1', '2'])) {
            abort(400, 'Tahap tidak valid');
        }

        // Ambil data utama
        $bidang = Bidang::userOnly()->findOrFail($bidang_id);
        $kegiatan = Kegiatan::userOnly()->findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::userOnly()->findOrFail($subkegiatan_id);

        // Ambil realisasi yang sesuai tahap
        $realisasi = Realisasi::userOnly()
            ->where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->when(!empty($tahap), function ($q) use ($tahap) {
                $q->where('tahap', $tahap);
            })
            ->first();

        return view('page.realisasi.create_sub_realisasi', compact(
            'bidang',
            'kegiatan',
            'tahap',
            'subKegiatan',
            'realisasi'
        ));
    }

    public function storeSub(Request $request)
    {
        // Validasi input
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'subkegiatan_id' => 'required|exists:sub_kegiatans,id',
            'tahap' => 'nullable|in:1,2',
            'volume_keluaran' => 'required|numeric',
            'tenaga_kerja' => 'nullable|numeric',
            'upah' => 'nullable|numeric',
            'BLT' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'realisasi_keuangan' => 'required|numeric',
            'durasi' => 'nullable|date',
            'KPM' => 'nullable|numeric',
            'cara_pengadaan' => 'required|string',
            'uraian_keluaran' => 'nullable|string',
            'tahun' => 'nullable|integer',
        ]);

        // Cari target
        $target = Target::userOnly()
            ->where('bidang_id', $request->bidang_id)
            ->where('kegiatan_id', $request->kegiatan_id)
            ->where('sub_kegiatan_id', $request->subkegiatan_id)
            ->first();

        if (!$target) {
            return redirect()->back()->with('error', 'Data target tidak ditemukan.');
        }

        DB::transaction(function () use ($request, $target) {
            $realisasi = Realisasi::userOnly()->updateOrCreate(
                [
                    'target_id' => $target->id,
                    'bidang_id' => $request->bidang_id,
                    'kegiatan_id' => $request->kegiatan_id,
                    'sub_kegiatan_id' => $request->subkegiatan_id,
                    'tahap' => $request->tahap,
                ],
                [
                    'user_id' => auth()->id(),
                    'volume_keluaran' => $request->volume_keluaran,
                    'uraian_keluaran' => $request->uraian_keluaran,
                    'tenaga_kerja' => $request->tenaga_kerja,
                    'cara_pengadaan' => $request->cara_pengadaan,
                    'realisasi_keuangan' => $request->realisasi_keuangan,
                    'durasi' => $request->durasi,
                    'upah' => $request->upah,
                    'KPM' => $request->KPM,
                    'BLT' => $request->BLT,
                    'tahun' => $request->tahun,
                    'keterangan' => $request->keterangan,
                ]
            );

            // Aggregate realisasi data for both tahap
            $totalRealisasi = Realisasi::userOnly()->where('target_id', $target->id)
                ->selectRaw('SUM(volume_keluaran) as total_volume, SUM(realisasi_keuangan) as total_keuangan')
                ->first();

            // Calculate persentase capaian volume
            $persenan_capaian_volume = 0;
            if ($target->volume_keluaran && is_numeric($target->volume_keluaran) && $target->volume_keluaran > 0) {
                $persenan_capaian_volume = ($totalRealisasi->total_volume / $target->volume_keluaran) * 100;
            }

            // Calculate persentase capaian keuangan
            $persenan_capaian_keuangan = 0;
            if ($target->anggaran_target && is_numeric($target->anggaran_target) && $target->anggaran_target > 0) {
                $persenan_capaian_keuangan = ($totalRealisasi->total_keuangan / $target->anggaran_target) * 100;
            }

            // Calculate sisa anggaran (target - realisasi)
            $sisa = ($target->anggaran_target ?? 0) - ($totalRealisasi->total_keuangan ?? 0);

            // Update or create Capaian
            Capaian::updateOrCreate(
                [
                    'target_id' => $target->id,
                    'user_id' => $realisasi->user_id,
                ],
                [
                    'persen_capaian_keluaran' => round($persenan_capaian_volume, 2),
                    'persen_capaian_keuangan' => round($persenan_capaian_keuangan, 2),
                    'sisa' => $sisa,
                ]
            );
        });


        return redirect()->route('desa.realisasi.index')->with('success', 'Data realisasi berhasil disimpan atau diperbarui.');
    }

    public function deleteSubKegiatan($id, $tahap = null)
    {
        // $id adalah id realisasi (untuk tahap spesifik jika tahap diberikan)
        $realisasi = Realisasi::userOnly()
            ->when($tahap, function ($q) use ($tahap) {
                $q->where('tahap', $tahap);
            })
            ->findOrFail($id);

        // set semua field realisasi jadi null (pertahankan record jika diperlukan, atau delete)
        $realisasi->update([
            'volume_keluaran' => null,
            'uraian_keluaran' => null,
            'tenaga_kerja' => null,
            'realisasi_keuangan' => null,
            'durasi' => null,
            'upah' => null,
            'KPM' => null,
            'BLT' => null,
            'keterangan' => null,
        ]);

        // opsional: reset capaian terkait
        $target = Target::userOnly()->where('id', $realisasi->target_id)->first();

        if ($target) {
            // Ambil total realisasi untuk target ini (termasuk semua tahap)
            $totalRealisasi = Realisasi::where('target_id', $target->id)
                ->selectRaw('SUM(volume_keluaran) as total_volume, SUM(realisasi_keuangan) as total_keuangan')
                ->first();

            // Hitung persentase capaian volume
            $persen_capaian_volume = 0;
            if ($target->volume_keluaran && is_numeric($target->volume_keluaran) && $target->volume_keluaran > 0) {
                $persen_capaian_volume = ($totalRealisasi->total_volume / $target->volume_keluaran) * 100;
            }

            // Hitung persentase capaian keuangan
            $persen_capaian_keuangan = 0;
            if ($target->anggaran_target && is_numeric($target->anggaran_target) && $target->anggaran_target > 0) {
                $persen_capaian_keuangan = ($totalRealisasi->total_keuangan / $target->anggaran_target) * 100;
            }

            // Hitung sisa anggaran
            $sisa = ($target->anggaran_target ?? 0) - ($totalRealisasi->total_keuangan ?? 0);

            // Update atau buat capaian
            Capaian::updateOrCreate(
                [
                    'target_id' => $target->id,
                    'user_id' => $realisasi->user_id,
                ],
                [
                    'persen_capaian_keluaran' => round($persen_capaian_volume, 2),
                    'persen_capaian_keuangan' => round($persen_capaian_keuangan, 2),
                    'sisa' => $sisa,
                ]
            );
        }

        return redirect()->back()->with('success', 'Data realisasi berhasil dihapus.');
    }
    public function detail($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $tahap = request()->input('tahap', 1);

        $query = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id);

        if ($tahap !== 'all') {
            $query->where('tahap', $tahap);
        }

        $realisasi = ($tahap === 'all')
            ? $query->get()
            : $query->firstOrFail();

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        return view('page.realisasi.detail', compact('realisasi', 'bidang', 'kegiatan', 'subKegiatan'));
    }
}
