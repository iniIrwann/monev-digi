<?php

use App\Http\Controllers\CapaianController;
use App\Http\Controllers\DataDesaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RealisasiKecamatanController;
use App\Http\Controllers\TargetDesaController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\TargetKecamatanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Login & Logout
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.view');
Route::post('login', [LoginController::class, 'authenticate'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Group route dengan middleware desa

Route::middleware(['auth', 'role:desa'])
    ->name('desa.') // prefix nama
    ->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
        // Resource
        Route::resource('target', TargetDesaController::class);
        Route::resource('realisasi', RealisasiController::class);
        Route::resource('capaian', CapaianController::class);

        // Target Detail, Edit
        Route::get('/target/detail-sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [TargetDesaController::class, 'detailSub'])->name('target.detail');
        Route::get('/target/edit-sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [TargetDesaController::class, 'editSub'])->name('target.edit.sub');
        Route::post('/target/update-sub', [TargetDesaController::class, 'updateSub'])->name('target.update.sub');

        // Target Tambah Data
        Route::post('/target/store-bidang', [TargetDesaController::class, 'storeBidang'])->name('target.store.bidang');
        Route::post('/target/store-kegiatan', [TargetDesaController::class, 'storeKegiatan'])->name('target.store.kegiatan');
        Route::post('/target/store-subkegiatan', [TargetDesaController::class, 'storeSubKegiatan'])->name('target.store.subkegiatan');
        Route::get('/subkegiatan/create/{bidang_id}/{kegiatan_id}', [TargetDesaController::class, 'createSubKegiatan'])->name('target.create.subkegiatan');

        // Target Update
        Route::put('/target/update-kegiatan/{id}', [TargetDesaController::class, 'updateKegiatan'])->name('target.update.kegiatan');
        Route::put('/target/update-bidang/{id}', [TargetDesaController::class, 'updateBidang'])->name('target.update.bidang');

        // Target Delete
        Route::delete('/target/delete-bidang/{id}', [TargetDesaController::class, 'deleteBidang'])->name('target.delete.bidang');
        Route::delete('/target/delete-kegiatan/{id}', [TargetDesaController::class, 'deleteKegiatan'])->name('target.delete.kegiatan');
        Route::delete('/target/delete-subKegiatan/{id}', [TargetDesaController::class, 'deleteSubKegiatan'])->name('target.delete.subKegiatan');

        // Realisasi
        Route::get('/realisasi/create/sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [RealisasiController::class, 'createSub'])->name('realisasi.create.sub');
        Route::post('/realisasi/store-sub', [RealisasiController::class, 'storeSub'])->name('realisasi.store.sub');
        Route::get('/realisasi/detail/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [RealisasiController::class, 'detail'])->name('realisasi.detail');
        Route::delete('/realisasi/delete-subKegiatan/{id}', [RealisasiController::class, 'deleteSubKegiatan'])->name('realisasi.delete.subKegiatan');

        // Capaian
        Route::get('/capaian/detail/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [CapaianController::class, 'detail'])->name('capaian.detail');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    });

Route::prefix('kecamatan')
    ->name('kecamatan.') // prefix nama
    ->middleware(['auth', 'role:kecamatan'])
    ->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'indexKec'])->name('dashboard.index');
        Route::resource('target', TargetKecamatanController::class);
        Route::resource('realisasi', RealisasiKecamatanController::class);
        Route::resource('capaian', CapaianController::class);


        Route::delete('/realisasi/delete-sub/{id}', [RealisasiKecamatanController::class, 'deleteSubKegiatan'])->name('realisasi.sub.delete');

        // Target Detail, Edit
        Route::get('/target/detail-sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [TargetKecamatanController::class, 'detailSub'])->name('target.detail');
        Route::get('/target/edit-sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [TargetKecamatanController::class, 'editSub'])->name('target.edit.sub');
        Route::post('/target/update-sub', [TargetKecamatanController::class, 'updateSub'])->name('target.update.sub');

        // Target Tambah Data
        Route::post('/target/store-bidang', [TargetKecamatanController::class, 'storeBidang'])->name('target.store.bidang');
        Route::post('/target/store-kegiatan', [TargetKecamatanController::class, 'storeKegiatan'])->name('target.store.kegiatan');
        Route::get('/subkegiatan/create/{bidang_id}/{kegiatan_id}', [TargetKecamatanController::class, 'createSubKegiatan'])->name('target.create.subkegiatan');
        Route::post('/target/store-subkegiatan', [TargetKecamatanController::class, 'storeSubKegiatan'])->name('target.store.subkegiatan');

        // Target Update
        Route::put('/target/update-kegiatan/{id}', [TargetKecamatanController::class, 'updateKegiatan'])->name('target.update.kegiatan');
        Route::put('/target/update-bidang/{id}', [TargetKecamatanController::class, 'updateBidang'])->name('target.update.bidang');

        // Target Delete
        Route::delete('/target/delete-bidang/{id}', [TargetKecamatanController::class, 'deleteBidang'])->name('target.delete.bidang');
        Route::delete('/target/delete-kegiatan/{id}', [TargetKecamatanController::class, 'deleteKegiatan'])->name('target.delete.kegiatan');
        Route::delete('/target/delete-subKegiatan/{id}', [TargetKecamatanController::class, 'deleteSubKegiatan'])->name('target.delete.subKegiatan');

        // Realisasi
        Route::get('/realisasi/create/sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [RealisasiKecamatanController::class, 'createSub'])->name('realisasi.create.sub');
        Route::post('/realisasi/store/sub', [RealisasiKecamatanController::class, 'storeSub'])->name('realisasi.store.sub');
        Route::get('/realisasi/detail/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [RealisasiKecamatanController::class, 'detail'])->name('realisasi.detail');
        Route::delete('/realisasi/delete-subKegiatan/{id}', [RealisasiKecamatanController::class, 'deleteSubKegiatan'])->name('realisasi.delete.subKegiatan');

        // Capaian
        Route::get('/capaian/detail/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [CapaianController::class, 'detail'])->name('capaianKec.detail');

        // Manajemen Desa
        Route::get('/data-desa', [DataDesaController::class, 'index'])->name('dataDesa.index');
        Route::get('/data-desa/detail/{id}', [DataDesaController::class, 'show'])->name('dataDesa.show');
        Route::get('/data-desa/create', [DataDesaController::class, 'create'])->name('dataDesa.create');
        Route::post('/data-desa/store', [DataDesaController::class, 'store'])->name('dataDesa.store');
        Route::get('/data-desa/edit/{id}', [DataDesaController::class, 'edit'])->name('dataDesa.edit');
        Route::put('/data-desa/update/{id}', [DataDesaController::class, 'update'])->name('dataDesa.update');
        Route::delete('/data-desa/delete/{id}', [DataDesaController::class, 'destroy'])->name('dataDesa.destroy');

        // Profile
        Route::get('/profile', [ProfileController::class, 'indexKec'])->name('profile.index');
        Route::get('/profile/edit/{id}', [ProfileController::class, 'editKec'])->name('profile.edit');
        Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    });
