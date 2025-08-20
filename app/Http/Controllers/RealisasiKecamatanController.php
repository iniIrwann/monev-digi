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
use Illuminate\Support\Facades\DB;

class RealisasiKecamatanController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $bidangId = $request->input('bidang');
        $search = trim($request->input('query'));
        $desaId = $request->input('desa');
        $tahap = $request->input('tahap', 'all');
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

        // Query dengan eager loading
        $query = Bidang::with([
            'kegiatan.subkegiatan.realisasis' => function ($q) use ($tahap) {
                if (in_array($tahap, ['1', '2'])) {
                    $q->where('tahap', (int) $tahap);
                }
            },
            'kegiatan.subkegiatan.targets'
        ]);

        if ($desaId) {
            $query->where('user_id', $desaId);
        }

        if ($bidangId) {
            $query->where('id', $bidangId);
        }

        if ($tahun) {
            $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahun, $tahap) {
                $q->where('tahun', $tahun);
                if (in_array($tahap, ['1', '2'])) {
                    $q->where('tahap', (int) $tahap);
                }
            });
        }

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
        $data = $query->paginate(5)->appends($request->query());

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
                        $totalVolumeRealisasi = ($sub->tahap1Data?->volume_keluaran ?? 0) + ($sub->tahap2Data?->volume_keluaran ?? 0);
                        $sub->persenVolumeFisikTotal = $sub->targetData && $sub->targetData->volume_keluaran > 0
                            ? ($totalVolumeRealisasi / $sub->targetData->volume_keluaran * 100)
                            : 0;
                        $totalKeuanganRealisasi = ($sub->tahap1Data?->realisasi_keuangan ?? 0) + ($sub->tahap2Data?->realisasi_keuangan ?? 0);
                        $sub->persenVolumeKeuanganTotal = $sub->targetData && $sub->targetData->anggaran_target > 0
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

        return view('page.kecamatan.realisasi.realisasi', compact(
            'data',
            'filterBidangs',
            'selectDesa',
            'bidang',
            'desa',
            'tahun',
            'tahap',
            'search'
        ));
    }

    public function createSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $tahap = request()->query('tahap', 'all');
        if (!empty($tahap) && !in_array($tahap, ['1', '2'])) {
            abort(400, 'Tahap tidak valid');
        }

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        $realisasi = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->when(!empty($tahap), function ($q) use ($tahap) {
                $q->where('tahap', $tahap);
            })
            ->first();

        return view('page.kecamatan.realisasi.create_sub_realisasi', compact(
            'bidang',
            'kegiatan',
            'subKegiatan',
            'realisasi',
            'tahap'
        ));
    }

    public function editSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $tahap = request()->query('tahap', 'all');
        if (!empty($tahap) && !in_array($tahap, ['1', '2'])) {
            abort(400, 'Tahap tidak valid');
        }

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        $realisasi = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->when(!empty($tahap), function ($q) use ($tahap) {
                $q->where('tahap', $tahap);
            })
            ->first();

        return view('page.kecamatan.realisasi.edit_sub_realisasi', compact(
            'bidang',
            'kegiatan',
            'subKegiatan',
            'realisasi',
            'tahap'
        ));
    }

    public function storeSub(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'subkegiatan_id' => 'required|exists:sub_kegiatans,id',
            'tahap' => 'nullable|in:1,2',
            'volume_keluaran' => 'required|numeric',
            // 'tenaga_kerja' => 'nullable|numeric',
            // 'upah' => 'nullable|numeric',
            // 'BLT' => 'nullable|numeric',
            'sasaran' => 'nullable|string',
            'realisasi_keuangan' => 'required|numeric',
            'durasi' => 'nullable|date',
            'KPM' => 'nullable|numeric',
            'cara_pengadaan' => 'required|string|max:100',
            'uraian_keluaran' => 'nullable|string|max:20',
            'tahun' => 'nullable|integer',
        ]);

        $tahap = $request->input('tahap', 1);

        $target = Target::where('bidang_id', $request->bidang_id)
            ->where('kegiatan_id', $request->kegiatan_id)
            ->where('sub_kegiatan_id', $request->subkegiatan_id)
            ->first();

        if (!$target) {
            return redirect()->back()->with('error', 'Data target tidak ditemukan.');
        }

        DB::transaction(function () use ($request, $target, $tahap) {
            // Validate request inputs
            $request->validate([
                'bidang_id' => 'required|exists:bidangs,id',
                'kegiatan_id' => 'required|exists:kegiatans,id',
                'subkegiatan_id' => 'required|exists:sub_kegiatans,id',
                'volume_keluaran' => 'required|numeric|min:0',
                'uraian_keluaran' => 'nullable|string|max:25',
                // 'tenaga_kerja' => 'nullable|numeric|min:0',
                'cara_pengadaan' => 'nullable|string|max:100',
                'realisasi_keuangan' => 'required|numeric|min:0',
                'durasi' => 'nullable|date|min:0',
                // 'upah' => 'nullable|numeric|min:0',
                'KPM' => 'nullable|numeric|min:0',
                // 'BLT' => 'nullable|numeric|min:0',
                'tahun' => 'required|integer|min:2000|max:2100',
                'sasaran' => 'nullable|string|max:20',
            ]);

            // Ensure user is authenticated
            if (!auth()->check()) {
                throw new \Exception('User must be authenticated to perform this action.');
            }

            $realisasi = Realisasi::updateOrCreate(
                [
                    'target_id' => $target->id,
                    'bidang_id' => $request->bidang_id,
                    'kegiatan_id' => $request->kegiatan_id,
                    'sub_kegiatan_id' => $request->subkegiatan_id,
                    'tahap' => $tahap,
                ],
                [
                    'user_id' => $target->user_id,
                    'volume_keluaran' => $request->volume_keluaran,
                    'uraian_keluaran' => $request->uraian_keluaran,
                    // 'tenaga_kerja' => $request->tenaga_kerja,
                    'cara_pengadaan' => $request->cara_pengadaan,
                    'realisasi_keuangan' => $request->realisasi_keuangan,
                    'durasi' => $request->durasi,
                    // 'upah' => $request->upah,
                    'KPM' => $request->KPM,
                    // 'BLT' => $request->BLT,
                    'tahun' => $request->tahun,
                    'sasaran' => $request->sasaran,
                ]
            );

            // Aggregate realisasi data for both tahap
            $totalRealisasi = Realisasi::where('target_id', $target->id)
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

        return redirect()->route('kecamatan.realisasi.index')->with('success', 'Data realisasi berhasil disimpan atau diperbarui.');
    }

    public function deleteSubKegiatan($id, $tahap = null)
    {
        $realisasi = Realisasi::when($tahap, function ($q) use ($tahap) {
            $q->where('tahap', $tahap);
        })->findOrFail($id);

        DB::transaction(function () use ($realisasi, $tahap) {
            // Reset data realisasi
            $realisasi->update([
                'volume_keluaran' => null,
                // 'tenaga_kerja' => null,
                // 'uraian_keluaran' => null,
                // 'cara_pengadaan' => null,
                'realisasi_keuangan' => null,
                'durasi' => null,
                'KPM' => null,
                'sasaran' => null,
                // 'upah' => null,
                // 'BLT' => null,
                // 'tahun' => null,
            ]);

            $target = Target::where('id', $realisasi->target_id)->first();

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
        });

        return redirect()->route('kecamatan.realisasi.index')->with('success', 'Data realisasi berhasil dihapus.');
    }


    public function detailSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $tahap = request()->input('tahap', 1);

        $query = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id);

        if ($tahap !== 'all') {
            $query->where('tahap', $tahap);
        }

        // kalau tahap = 'all', ambil semua data, kalau tidak ambil satu data saja
        $realisasi = ($tahap === 'all')
            ? $query->get()
            : $query->firstOrFail();

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        return view('page.kecamatan.realisasi.detail', compact('realisasi', 'bidang', 'kegiatan', 'subKegiatan'));
    }


    public function createCatatan($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        $realisasis = Realisasi::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->get();

        if ($realisasis->isEmpty()) {
            abort(404, 'Realisasi not found');
        }

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        return view('page.kecamatan.realisasi.create_catatan', compact('realisasis', 'bidang', 'kegiatan', 'subKegiatan'));
    }
}
