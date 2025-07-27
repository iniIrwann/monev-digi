<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use App\Models\SubKegiatan;
use App\Models\target;
use App\Models\Capaian;
use Illuminate\Http\Request;

class TargetController extends Controller
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
        $query = Bidang::userOnly()->with(['kegiatan.subkegiatan.targets']);

        // Filter Tahun
        if ($tahun) {
            $query->whereHas('kegiatan.subkegiatan.targets', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }

        // ✅ Tetap filter berdasarkan bidang jika dipilih
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
                    ->orWhereHas('kegiatan.subkegiatan.targets', function ($q2) use ($search) {
                        $q2->where('uraian_keluaran', 'like', "%$search%");
                    });
            });
        }

        // Pagination
        $data = $query->paginate(5)->appends($request->query());

        return view('page.target.target', compact('data', 'filterBidangs'));
    }

    public function detailSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        // Validasi dan ambil data
        $bidang = Bidang::userOnly()->findOrFail($bidang_id);
        $kegiatan = Kegiatan::userOnly()->findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::userOnly()->findOrFail($subkegiatan_id);
        $target = Target::userOnly()->where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();
        // $uraian_keluaran = $target ? $target->uraian_keluaran : '';

        // Logic to show the form for creating a new sub
        return view('page.target.detail_sub_target', compact('bidang', 'kegiatan', 'subKegiatan', 'target'));
    }
    public function editSub($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        // Validasi dan ambil data
        $bidang = Bidang::userOnly()->findOrFail($bidang_id);
        $kegiatan = Kegiatan::userOnly()->findOrFail($kegiatan_id);
        $subKegiatan = SubKegiatan::userOnly()->findOrFail($subkegiatan_id);
        $target = Target::userOnly()->where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->first();
        // $uraian_keluaran = $target ? $target->uraian_keluaran : '';

        // Logic to show the form for creating a new sub
        return view('page.target.edit_sub_target', compact('bidang', 'kegiatan', 'subKegiatan', 'target'));
    }

    public function updateSub(Request $request)
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
            'anggaran_target' => 'required|numeric',
            'durasi' => 'nullable|numeric',
            'KPM' => 'nullable|numeric',
        ]);

        // Cari entri target berdasarkan kombinasi bidang, kegiatan, subkegiatan, dan tahun
        $target = Target::userOnly()
            ->where('bidang_id', $request->bidang_id)
            ->where('kegiatan_id', $request->kegiatan_id)
            ->where('sub_kegiatan_id', $request->subkegiatan_id)
            ->first();

        if (!$target) {
            return redirect()->route('target.index')->with('error', 'Data target tidak ditemukan.');
        }

        // Update atau isi data
        $target->volume_keluaran = $request->volume_keluaran;
        $target->cara_pengadaan = $request->cara_pengadaan;
        $target->uraian_keluaran = $request->uraian_keluaran;
        $target->anggaran_target = $request->anggaran_target;
        $target->tenaga_kerja = $request->tenaga_kerja;
        $target->durasi = $request->durasi;
        $target->upah = $request->upah;
        $target->KPM = $request->KPM;
        $target->BLT = $request->BLT;
        $target->tahun = $request->tahun;
        $target->keterangan = $request->keterangan;
        $target->save();

        return redirect()->route('target.index')->with('success', 'Data target berhasil disimpan atau diperbarui.');
    }
    public function storeBidang(Request $request)
    {
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Hitung jumlah bidang saat ini
        $jumlahBidang = Bidang::where('user_id', auth()->id())->count();

        // Maksimum hanya sampai Z (26 data)
        if ($jumlahBidang >= 26) {
            return back()->with('error', 'Jumlah kode bidang maksimal hanya sampai Z.');
        }

        // Generate huruf berdasarkan urutan A-Z
        $kode = chr(65 + $jumlahBidang); // 65 = ASCII 'A'

        $bidang = new Bidang();
        $bidang->kode_rekening = $kode;
        $bidang->user_id = auth()->id();
        $bidang->nama_bidang = $request->nama_bidang;
        $bidang->keterangan = $request->keterangan;
        $bidang->save();

        return redirect()->route('target.index')
            ->with('success', 'Bidang berhasil ditambahkan.');
    }
    public function storeKegiatan(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
        ]);

        // Cari kode_rekening terakhir untuk bidang terkait dan user yang sedang login
        $lastKegiatan = Kegiatan::where('bidang_id', $request->bidang_id)
            ->orderByDesc('kode_rekening')
            ->first();

        // Tentukan kode_rekening baru
        $kodeRekening = $lastKegiatan
            ? $lastKegiatan->kode_rekening + 1
            : 1;

        // Simpan kegiatan baru
        $kegiatan = new Kegiatan();
        $kegiatan->bidang_id = $request->bidang_id;
        $kegiatan->user_id = auth()->id();
        $kegiatan->kode_rekening = $kodeRekening;
        $kegiatan->nama_kegiatan = $request->kegiatan;
        $kegiatan->kategori = $request->kategori;
        $kegiatan->save();

        return redirect()->route('target.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function storeSubKegiatan(Request $request)
    {
        $request->validate([
            'bidang_id' => 'required|exists:bidangs,id',
            'kegiatan_id' => 'required|exists:kegiatans,id',
            'nama_subkegiatan' => 'required|string|max:255',
            'uraian_keluaran' => 'required|string|max:255',
            'volume_keluaran' => 'required|numeric',
            'tenaga_kerja' => 'nullable|numeric',
            'upah' => 'nullable|numeric',
            'BLT' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'cara_pengadaan' => 'nullable|string|max:255',
            'tahun' => 'required|numeric',
            'anggaran_target' => 'nullable|numeric',
            'durasi' => 'nullable|numeric',
            'KPM' => 'nullable|numeric',
        ]);

        // Ambil jumlah subkegiatan saat ini untuk kegiatan terkait
        $lastSub = SubKegiatan::where('kegiatan_id', $request->kegiatan_id)->userOnly()->count();
        $nextKodeSub = $lastSub + 1;

        // Simpan ke tabel subkegiatan
        $sub = SubKegiatan::create([
            'bidang_id' => $request->bidang_id,
            'kegiatan_id' => $request->kegiatan_id,
            'user_id' => auth()->id(),
            'kode_rekening' => $nextKodeSub,
            'nama_subkegiatan' => $request->nama_subkegiatan,
            'uraian_keluaran' => $request->uraian_keluaran,
        ]);

        $realisasi = Realisasi::create([
            'bidang_id' => $request->bidang_id,
            'kegiatan_id' => $request->kegiatan_id,
            'sub_kegiatan_id' => $sub->id,
            'user_id' => auth()->id(),
            'uraian_keluaran' => $request->uraian_keluaran,
        ]);

        // Simpan ke tabel target
        $target = Target::create([
            'bidang_id' => $request->bidang_id,
            'kegiatan_id' => $request->kegiatan_id,
            'sub_kegiatan_id' => $sub->id,
            'user_id' => auth()->id(),
            'uraian_keluaran' => $request->uraian_keluaran,
            'volume_keluaran' => $request->volume_keluaran,
            'cara_pengadaan' => $request->cara_pengadaan,
            'anggaran_target' => $request->anggaran_target,
            'tenaga_kerja' => $request->tenaga_kerja,
            'durasi' => $request->durasi,
            'upah' => $request->upah,
            'KPM' => $request->KPM,
            'BLT' => $request->BLT,
            'tahun' => $request->tahun,
            'keterangan' => $request->keterangan,
        ]);
        Capaian::create([
            'target_id' => $target->id,
            'realisasi_id' => $realisasi->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('target.index')
            ->with('success', 'Sub Kegiatan dan Target berhasil ditambahkan.');
    }

    public function createSubKegiatan($bidang_id, $kegiatan_id)
    {
        // Validasi dan ambil data
        $bidang = Bidang::userOnly()->findOrFail($bidang_id);
        $kegiatan = Kegiatan::userOnly()->findOrFail($kegiatan_id);

        return view('page.target.create_sub_kegiatan', compact('bidang', 'kegiatan'));
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

        $kegiatan = Kegiatan::userOnly()->findOrFail($id);
        $kegiatan->nama_kegiatan = $request->kegiatan;
        $kegiatan->kategori = $request->kategori;
        $kegiatan->save();

        return redirect()->route('target.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }
    public function updateBidang(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nama_bidang' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $bidang = Bidang::userOnly()->findOrFail($id);
        $bidang->nama_bidang = $request->nama_bidang;
        $bidang->keterangan = $request->keterangan;
        $bidang->save();

        return redirect()->route('target.index')
            ->with('success', 'Bidang berhasil diperbarui.');
    }
    // Delete
    public function deleteBidang(string $id)
    {
        $bidang = Bidang::userOnly()->findOrFail($id);

        // Hapus semua kegiatan dan subkegiatan terkait
        foreach ($bidang->kegiatan as $kegiatan) {
            foreach ($kegiatan->subkegiatan as $subKegiatan) {
                $subKegiatan->targets()->delete();
            }
            $kegiatan->subkegiatan()->delete();
        }

        // Hapus bidang
        $bidang->delete();

        return redirect()->route('target.index')
            ->with('success', 'Bidang beserta seluruh Kegiatan dan Sub Kegiatan berhasil dihapus.');
    }
    public function deleteKegiatan(string $id)
    {
        $kegiatan = Kegiatan::userOnly()->findOrFail($id);

        // Hapus semua subkegiatan dan target terkait
        foreach ($kegiatan->subkegiatan as $subKegiatan) {
            $subKegiatan->targets()->delete();
        }
        $kegiatan->subkegiatan()->delete();

        // Hapus kegiatan
        $kegiatan->delete();

        return redirect()->route('target.index')
            ->with('success', 'Kegiatan beserta seluruh Sub Kegiatan berhasil dihapus.');
    }
    public function deleteSubKegiatan(string $id)
    {
        $subKegiatan = SubKegiatan::userOnly()->findOrFail($id);

        // Hapus semua target terkait
        $subKegiatan->targets()->delete();

        // Hapus subkegiatan
        $subKegiatan->delete();

        return redirect()->route('target.index')
            ->with('success', 'Sub Kegiatan berhasil dihapus.');
    }
}
