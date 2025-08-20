<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\SubKegiatan;
use App\Models\Target;
use App\Models\User;
use App\Models\Verifikasi;
use Illuminate\Http\Request;

class VerifikasiKecamatanController extends Controller
{
    public function index(Request $request)
    {
        // Validate and sanitize inputs
        $tahun = $request->input('tahun');
        $bidangId = $request->input('bidang');
        $search = trim($request->input('query'));
        $desaId = $request->input('desa');
        $tahap = $request->input('tahap', 'all'); // Default to Tahap 1

        // Validate tahap
        if (!in_array($tahap, ['1', '2', 'all'])) {
            $tahap = 'all'; // Fallback to default if invalid
        }

        // Fetch desa and bidang if provided
        $desa = $desaId ? User::where('role', 'desa')->where('id', $desaId)->first() : null;
        $bidang = $bidangId ? Bidang::find($bidangId) : null;

        // Fetch desa options for filter
        $selectDesa = User::where('role', 'desa')->select('id', 'desa')->get();

        // Fetch bidang options based on desa
        $filterBidangs = $desaId
            ? Bidang::where('user_id', $desaId)->select('id', 'nama_bidang')->get()
            : collect();

        // Build query with eager loading
        $query = Bidang::with([
            'kegiatan.subkegiatan.realisasis.verifikasi',
            'kegiatan.subkegiatan.targets'
        ]);

        // Apply filters
        if ($desaId) {
            $query->where('user_id', $desaId);
        }
        if ($bidangId) {
            $query->where('id', $bidangId);
        }
        if ($tahun) {
            $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }
        if ($tahap !== 'all') {
            $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahap) {
                $q->where('tahap', $tahap);
            });
        }
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

        // Fetch paginated data
        $data = $query->paginate(5)->appends($request->query());

        // Process data for Blade template
        foreach ($data as $bidang) {
            foreach ($bidang->kegiatan as $kegiatan) {
                foreach ($kegiatan->subkegiatan as $sub) {
                    // Set realisasi for the selected tahap
                    $sub->realisasi = $sub->realisasis->where('tahap', $tahap)->first();

                    // Set target
                    $sub->target = $sub->targets->first();

                    // Set tahap1Data and tahap2Data for 'all' view
                    if ($tahap === 'all') {
                        $sub->tahap1Data = $sub->realisasis->where('tahap', 1)->first();
                        $sub->tahap2Data = $sub->realisasis->where('tahap', 2)->first();

                        // Calculate totals and percentages
                        if ($sub->tahap1Data && $sub->tahap2Data && $sub->target) {
                            $totalVolume = ($sub->tahap1Data->volume_keluaran ?? 0) + ($sub->tahap2Data->volume_keluaran ?? 0);
                            $totalKeuangan = ($sub->tahap1Data->realisasi_keuangan ?? 0) + ($sub->tahap2Data->realisasi_keuangan ?? 0);
                            $sub->persenVolumeFisikTotal = $sub->target->volume_keluaran
                                ? ($totalVolume / $sub->target->volume_keluaran) * 100
                                : 0;
                            $sub->persenVolumeKeuanganTotal = $sub->target->anggaran_target
                                ? ($totalKeuangan / $sub->target->anggaran_target) * 100
                                : 0;
                        } else {
                            $sub->persenVolumeFisikTotal = null;
                            $sub->persenVolumeKeuanganTotal = null;
                        }
                    } else {
                        // Set tahapData for single tahap view
                        $sub->tahapData = $sub->realisasis->where('tahap', $tahap)->first();

                        // Calculate percentages for single tahap
                        if ($sub->tahapData && $sub->target) {
                            $sub->persenVolumeFisik = $sub->target->volume_keluaran
                                ? ($sub->tahapData->volume_keluaran / $sub->target->volume_keluaran) * 100
                                : 0;
                            $sub->persenKeuangan = $sub->target->anggaran_target
                                ? ($sub->tahapData->realisasi_keuangan / $sub->target->anggaran_target) * 100
                                : 0;
                        } else {
                            $sub->persenVolumeFisik = null;
                            $sub->persenKeuangan = null;
                        }
                    }
                }
            }
        }

        return view('page.kecamatan.verifikasi.index', compact(
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'realisasi_id' => 'required|exists:realisasis,id',
            'catatan' => 'required|string|max:255',
            'tindak_lanjut' => 'required|string|max:255',
            'rekomendasi' => 'required|string|max:255',
        ]);

        $realisasi = Realisasi::findOrFail($validated['realisasi_id']);

        if (auth()->user()->role !== 'kecamatan') {
            return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki izin untuk menyimpan verifikasi.']);
        }

        $verifikasi = Verifikasi::updateOrCreate(
            ['id' => $realisasi->verifikasi_id],
            [
                'catatan' => $validated['catatan'],
                'tindak_lanjut' => $validated['tindak_lanjut'],
                'rekomendasi' => $validated['rekomendasi'],
                'user_id' => $realisasi->user_id,
            ]
        );

        $cariSemuaRealisasi = Realisasi::where('target_id', $realisasi->target_id)
            ->where('bidang_id', $realisasi->bidang_id)
            ->where('kegiatan_id', $realisasi->kegiatan_id)
            ->where('sub_kegiatan_id', $realisasi->sub_kegiatan_id)
            ->update(['verifikasi_id' => $verifikasi->id]);


        return redirect()->route('kecamatan.verifikasi.index', $request->query())
            ->with('success', 'Data verifikasi berhasil disimpan.');
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

        $realisasi = ($tahap === 'all')
            ? $query->get()
            : $query->firstOrFail();

        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        return view('page.kecamatan.verifikasi.detail', compact('realisasi', 'bidang', 'kegiatan', 'subKegiatan'));
    }
}
