<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\SubKegiatan;
use App\Models\Target;
use App\Models\User;
use App\Models\Verifikasi;
use DB;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $bidangId = $request->input('bidang');
        $search = $request->input('query');
        $desaId = $request->input('desa');
        $tahap = $request->input('tahap', '1'); // Default ke Tahap 1

        $desa = null;
        $bidang = null;
        if ($desaId) {
            $desa = User::where('role', 'desa')->where('id', $desaId)->first();
        }
        if ($bidangId) {
            $bidang = Bidang::find($bidangId);
        }

        $selectDesa = User::where('role', 'desa')->select('id', 'desa')->get();
        $filterBidangs = collect();
        if ($desaId) {
            $filterBidangs = Bidang::where('user_id', $desaId)
                ->select('id', 'nama_bidang')
                ->get();
        }

        $query = Bidang::with(['kegiatan.subkegiatan.realisasis.verifikasi']);

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
        if ($tahap) {
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

        $data = $query->paginate(5)->appends($request->query());

        foreach ($data as $bidang) {
            foreach ($bidang->kegiatan as $kegiatan) {
                foreach ($kegiatan->subkegiatan as $sub) {
                    $sub->realisasi = $sub->realisasis->where('tahap', $tahap)->first();

                    $sub->target = Target::where('sub_kegiatan_id', $sub->id)->first();
                }
            }
        }

        return view('page.kecamatan.verifikasi.index', [
            'data' => $data,
            'filterBidangs' => $filterBidangs,
            'selectDesa' => $selectDesa,
            'bidang' => $bidang,
            'desa' => $desa,
            'tahun' => $tahun,
            'tahap' => $tahap
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'realisasi_id' => 'required|exists:realisasis,id',
            'catatan' => 'required|string',
            'tindak_lanjut' => 'required|string',
            'rekomendasi' => 'required|string',
        ]);

        $realisasi = Realisasi::findOrFail($validated['realisasi_id']);

        // Ambil user_id dari realisasi, jangan dari input client
        $userId = $realisasi->user_id;
        if (!$userId) {
            return redirect()->back()->withErrors(['realisasi_id' => 'Realisasi tidak terkait dengan user yang valid.'])->withInput();
        }

        $verifikasi = Verifikasi::create([
            'catatan' => $validated['catatan'],
            'tindak_lanjut' => $validated['tindak_lanjut'],
            'rekomendasi' => $validated['rekomendasi'],
        ]);

        Realisasi::where('bidang_id', $realisasi->bidang_id)
            ->where('kegiatan_id', $realisasi->kegiatan_id)
            ->where('sub_kegiatan_id', $realisasi->sub_kegiatan_id)
            ->where('user_id', $realisasi->user_id)
            ->update([
                'verifikasi_id' => $verifikasi->id
            ]);

        return redirect()->route('kecamatan.verifikasi.index', $request->query())
            ->with('success', 'Data verifikasi berhasil disimpan.');
    }

}
