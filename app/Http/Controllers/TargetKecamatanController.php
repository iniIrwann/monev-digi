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

class TargetKecamatanController extends Controller
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

        $query = Bidang::with(['kegiatan.subkegiatan.targets']);

        if ($desaId) {
            $query->where('user_id', $desaId);
        }

        if (!empty($bidangId) && is_numeric($bidangId)) {
            $query->where('id', $bidangId);
            $bidang = Bidang::find($bidangId);
        }

        if (!empty($tahun) && is_numeric($tahun)) {
            $query->whereHas('kegiatan.subkegiatan.targets', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }


        // Filter: pencarian teks (jika nanti kamu tambahkan input text)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_bidang', 'like', "%$search%")
                    ->orWhereHas('kegiatan', function ($q2) use ($search) {
                        $q2->where('nama_kegiatan', 'like', "%$search%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan', function ($q2) use ($search) {
                        $q2->where('nama_subkegiatan', 'like', "%$search%");
                    })
                    ->orWhereHas('kegiatan.subkegiatan.targets', function ($q2) use ($search) {
                        $q2->where('uraian_keluaran', 'like', "%$search%");
                    });
            });
        }

        // Pagination + simpan query string filter
        $data = $query->paginate(5)->appends($request->query());

        return view('page.kecamatan.target.target', compact('data', 'filterBidangs', 'selectDesa', 'bidang', 'desa', 'tahun'));
    }




    public function detailSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        // Validasi dan ambil data
        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);
        $target = Target::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();
        // $uraian_keluaran = $target ? $target->uraian_keluaran : '';

        // Logic to show the form for creating a new sub
        return view('page.kecamatan.target.detail_sub_target', compact('bidang', 'kegiatan', 'subKegiatan', 'target'));
    }
    public function editSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        // Validasi dan ambil data
        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::findOrFail($subkegiatan_id);
        $target = Target::where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();
        // $uraian_keluaran = $target ? $target->uraian_keluaran : '';

        // Logic to show the form for creating a new sub
        return view('page.kecamatan.target.edit_sub_target', compact('bidang', 'kegiatan', 'subKegiatan', 'target'));
    }

    public function updateSub(Request $request)
    {
        // Validasi input
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'subkegiatan_id' => 'required|exists:sub_kegiatans,id',
            'nama_subkegiatan' => 'required|string|max:100',
            'uraian_keluaran' => 'required|string|max:20',
            'volume_keluaran' => 'required|numeric',
            // 'tenaga_kerja' => 'nullable|numeric',
            // 'upah' => 'nullable|numeric',
            // 'BLT' => 'nullable|numeric',
            'sasaran' => 'nullable|string|max:20',
            'cara_pengadaan' => 'required|string|max:100',
            'tahun' => 'required|numeric',
            'anggaran_target' => 'required|numeric',
            'durasi' => 'nullable|date',
            'KPM' => 'nullable|numeric',
        ]);

        $subkegiatan = SubKegiatan::findOrFail($request->subkegiatan_id);

        // Cari entri target berdasarkan kombinasi bidang, kegiatan, subkegiatan, dan tahun
        $target = Target::where('bidang_id', $request->bidang_id)
            ->where('kegiatan_id', $request->kegiatan_id)
            ->where('sub_kegiatan_id', $request->subkegiatan_id)
            ->first();

        if (!$target) {
            return redirect()->back()->with('error', 'Data target tidak ditemukan.');
        }

        // Update Sub
        $subkegiatan->nama_subkegiatan = $request->nama_subkegiatan;
        $subkegiatan->save();

        $realisasi1 = Realisasi::updateOrCreate([
            'target_id' => $target->id,
            'bidang_id' => $request->bidang_id,
            'kegiatan_id' => $request->kegiatan_id,
            'sub_kegiatan_id' => $subkegiatan->id,
            'user_id' => $subkegiatan->user_id,
            'tahap' => 1,
        ], [
            'uraian_keluaran' => $request->uraian_keluaran,
            'cara_pengadaan' => $request->cara_pengadaan,
            'tahun' => $request->tahun,
        ]);
        $realisasi2 = Realisasi::updateOrCreate([
            'target_id' => $target->id,
            'bidang_id' => $request->bidang_id,
            'kegiatan_id' => $request->kegiatan_id,
            'sub_kegiatan_id' => $subkegiatan->id,
            'user_id' => $subkegiatan->user_id,
            'tahap' => 2,
        ], [
            'uraian_keluaran' => $request->uraian_keluaran,
            'cara_pengadaan' => $request->cara_pengadaan,
            'tahun' => $request->tahun,
        ]);

        // Update atau isi data
        $target->volume_keluaran = $request->volume_keluaran;
        $target->cara_pengadaan = $request->cara_pengadaan;
        $target->uraian_keluaran = $request->uraian_keluaran;
        $target->anggaran_target = $request->anggaran_target;
        // $target->tenaga_kerja = $request->tenaga_kerja;
        $target->durasi = $request->durasi;
        // $target->upah = $request->upah;
        $target->KPM = $request->KPM;
        // $target->BLT = $request->BLT;
        $target->tahun = $request->tahun;
        $target->sasaran = $request->sasaran;
        $target->save();

        return redirect()->route('kecamatan.target.index')->with('success', 'Data target berhasil disimpan atau diperbarui.');
    }
    public function storeBidang(Request $request)
    {
        $request->validate([
            'desa' => 'required|exists:users,id',
            'nama_bidang' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $desaValid = User::where('id', $request->desa)->where('role', 'desa')->exists();

        if (!$desaValid) {
            return back()->with('error', 'Desa tidak valid.');
        }

        // Ambil semua kode existing untuk user
        $existing = Bidang::where('user_id', $request->desa)->pluck('kode_rekening')->toArray();

        // Cari huruf A-Z yang belum dipakai
        $kode = null;
        for ($i = 0; $i < 26; $i++) {
            $candidate = chr(65 + $i); // A..Z
            if (!in_array($candidate, $existing)) {
                $kode = $candidate;
                break;
            }
        }

        if (!$kode) {
            return back()->with('error', 'Jumlah kode bidang maksimal hanya sampai Z.');
        }

        $bidang = new Bidang();
        $bidang->kode_rekening = $kode;
        $bidang->user_id = $request->desa;
        $bidang->nama_bidang = $request->nama_bidang;
        $bidang->keterangan = $request->keterangan;
        $bidang->save();

        return redirect()->back()
            ->with('success', 'Bidang berhasil ditambahkan.');
    }
    public function storeKegiatan(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
        ]);

        $bidang = Bidang::findOrFail($request->bidang_id);

        $existingCodes = Kegiatan::where('bidang_id', $request->bidang_id)
            ->where('user_id', $bidang->user_id)
            ->pluck('kode_rekening')
            ->toArray();

        // Cari angka terkecil yang belum dipakai (mulai dari 1)
        $kodeRekening = 1;
        while (in_array($kodeRekening, $existingCodes)) {
            $kodeRekening++;
        }

        // Simpan kegiatan baru
        $kegiatan = new Kegiatan();
        $kegiatan->bidang_id = $request->bidang_id;
        $kegiatan->user_id = $bidang->user_id;
        $kegiatan->kode_rekening = $kodeRekening;
        $kegiatan->nama_kegiatan = $request->kegiatan;
        $kegiatan->kategori = $request->kategori;
        $kegiatan->save();

        return redirect()->back()
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function storeSubKegiatan(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'nama_subkegiatan' => 'required|string|max:100',
            'uraian_keluaran' => 'required|string|max:20',
            'volume_keluaran' => 'required|numeric',
            // 'tenaga_kerja' => 'nullable|numeric',
            // 'upah' => 'nullable|numeric',
            // 'BLT' => 'nullable|numeric',
            'sasaran' => 'nullable|string|max:20',
            'cara_pengadaan' => 'nullable|string|max:100',
            'tahun' => 'required|numeric',
            'anggaran_target' => 'nullable|numeric',
            'durasi' => 'nullable|date',
            'KPM' => 'nullable|numeric',
        ]);

        $bidang = Bidang::findOrFail($request->bidang_id);

        $existingCodes = SubKegiatan::where('kegiatan_id', $request->kegiatan_id)
            ->where('user_id', $bidang->user_id)
            ->pluck('kode_rekening')
            ->toArray();


        $nextKodeSub = 1;
        while (in_array($nextKodeSub, $existingCodes)) {
            $nextKodeSub++;
        }

        // Simpan ke tabel subkegiatan
        $sub = SubKegiatan::create([
            'bidang_id' => $request->bidang_id,
            'kegiatan_id' => $request->kegiatan_id,
            'user_id' => $bidang->user_id,
            'kode_rekening' => $nextKodeSub,
            'nama_subkegiatan' => $request->nama_subkegiatan,
        ]);
        // Simpan ke tabel target
        $target = Target::create([
            'bidang_id' => $request->bidang_id,
            'kegiatan_id' => $request->kegiatan_id,
            'sub_kegiatan_id' => $sub->id,
            'user_id' => $bidang->user_id,
            'uraian_keluaran' => $request->uraian_keluaran,
            'volume_keluaran' => $request->volume_keluaran,
            'cara_pengadaan' => $request->cara_pengadaan,
            'anggaran_target' => $request->anggaran_target,
            // 'tenaga_kerja' => $request->tenaga_kerja,
            'durasi' => $request->durasi,
            // 'upah' => $request->upah,
            'KPM' => $request->KPM,
            // 'BLT' => $request->BLT,
            'tahun' => $request->tahun,
            'sasaran' => $request->sasaran,
        ]);
        $realisasi1 = Realisasi::updateOrCreate([
            'target_id' => $target->id,
            'bidang_id' => $request->bidang_id,
            'kegiatan_id' => $request->kegiatan_id,
            'sub_kegiatan_id' => $sub->id,
            'user_id' => $bidang->user_id,
            'tahap' => 1,
            'uraian_keluaran' => $request->uraian_keluaran,
            'cara_pengadaan' => $request->cara_pengadaan,
            'tahun' => $request->tahun,
        ]);
        $realisasi2 = Realisasi::updateOrCreate([
            'target_id' => $target->id,
            'bidang_id' => $request->bidang_id,
            'kegiatan_id' => $request->kegiatan_id,
            'sub_kegiatan_id' => $sub->id,
            'user_id' => $bidang->user_id,
            'tahap' => 2,
            'uraian_keluaran' => $request->uraian_keluaran,
            'cara_pengadaan' => $request->cara_pengadaan,
            'tahun' => $request->tahun,
        ]);

        Capaian::create([
            'target_id' => $target->id,
            'user_id' => $bidang->user_id,
        ]);

        return redirect()->route('kecamatan.target.index')
            ->with('success', 'Sub Kegiatan dan Target berhasil ditambahkan.');
    }

    public function createSubKegiatan($bidang_id, $kegiatan_id)
    {
        // Validasi dan ambil data
        $bidang = Bidang::findOrFail($bidang_id);
        $kegiatan = Kegiatan::findOrFail($kegiatan_id);

        return view('page.kecamatan.target.create_sub_kegiatan', compact('bidang', 'kegiatan'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function updateKegiatan(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'kegiatan' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->nama_kegiatan = $request->kegiatan;
        $kegiatan->kategori = $request->kategori;
        $kegiatan->save();

        return redirect()->back()
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }
    public function updateBidang(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $bidang = Bidang::findOrFail($id);
        $bidang->nama_bidang = $request->nama_bidang;
        $bidang->keterangan = $request->keterangan;
        $bidang->save();

        return redirect()->back()
            ->with('success', 'Bidang berhasil diperbarui.');
    }
    // Delete
    public function deleteBidang(string $id)
    {
        $bidang = Bidang::findOrFail($id);

        // Hapus semua kegiatan dan subkegiatan terkait
        foreach ($bidang->kegiatan as $kegiatan) {
            foreach ($kegiatan->subkegiatan as $subKegiatan) {
                $subKegiatan->targets()->delete();
            }
            $kegiatan->subkegiatan()->delete();
        }

        // Hapus bidang
        $bidang->delete();

        return redirect()->back()
            ->with('success', 'Bidang beserta seluruh Kegiatan dan Sub Kegiatan berhasil dihapus.');
    }
    public function deleteKegiatan(string $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Hapus semua subkegiatan dan target terkait
        foreach ($kegiatan->subkegiatan as $subKegiatan) {
            $subKegiatan->targets()->delete();
        }
        $kegiatan->subkegiatan()->delete();

        // Hapus kegiatan
        $kegiatan->delete();

        return redirect()->route('kecamatan.target.index')
            ->with('success', 'Kegiatan beserta seluruh Sub Kegiatan berhasil dihapus.');
    }
    public function deleteSubKegiatan(string $id)
    {
        $subKegiatan = SubKegiatan::findOrFail($id);

        // Hapus semua target terkait
        $subKegiatan->targets()->delete();

        // Hapus subkegiatan
        $subKegiatan->delete();

        return redirect()->back()
            ->with('success', 'Sub Kegiatan berhasil dihapus.');
    }
}
