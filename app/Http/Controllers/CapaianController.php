<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Realisasi;
use App\Models\Target;
use Illuminate\Http\Request;

class CapaianController extends Controller
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
        $query = Bidang::userOnly()->with(['kegiatan.subkegiatan.realisasis.capaian.target']);

        // Filter Tahun
        if ($tahun) {
            $query->whereHas('kegiatan.subkegiatan.realisasis', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }

        // $persen = $request->input('persen');

        // if ($persen !== null) {
        //     $query->whereHas('kegiatan.subkegiatan.realisasis.capaian', function ($q) use ($persen) {
        //         $q->where('persen_capaian_keluaran', '>=', $persen);
        //     });
        // }


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

        return view('page.capaian.capaian', compact('data', 'filterBidangs'));
    }

    public function detail($bidang_id, $kegiatan_id, $subkegiatan_id)
    {
        // Ambil data capaian lengkap berdasarkan relasi
        $capaian = Bidang::userOnly()->with(['kegiatan.subkegiatan.realisasis.capaian.target'])
            ->where('id', $bidang_id)
            ->first();

        if (!$capaian) {
            abort(404, 'Capaian not found');
        }
        // Ambil realisasi berdasarkan bidang, kegiatan, dan subkegiatan
        $realisasi = Realisasi::userOnly()->where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->whereHas('capaian')
            ->first();
        $target = Target::userOnly()->where('bidang_id', $bidang_id)
            ->where('kegiatan_id', $kegiatan_id)
            ->where('sub_kegiatan_id', $subkegiatan_id)
            ->whereHas('capaian')
            ->first();

        $bidang = Bidang::userOnly()->findOrFail($bidang_id);
        $kegiatan = $bidang->kegiatan()->findOrFail($kegiatan_id);
        $subkegiatan = $kegiatan->subkegiatan()->findOrFail($subkegiatan_id);

        return view('page.capaian.detail', compact('capaian', 'bidang', 'kegiatan', 'subkegiatan', 'realisasi', 'target'));
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
