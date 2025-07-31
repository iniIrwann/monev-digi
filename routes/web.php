<?php

use App\Http\Controllers\CapaianController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
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

// Group route dengan middleware auth
Route::middleware(['auth', 'role:desa'])->group(function () {
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

Route::middleware(['auth', 'role:kecamatan'])->prefix('kecamatan')->group(function () {

    Route::resource('targetKec', TargetKecamatanController::class);
    Route::resource('realisasiKec', RealisasiController::class);
    Route::resource('capaianKec', CapaianController::class);

    // Target Detail, Edit
    Route::get('/targetKec/detail-sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [TargetKecamatanController::class, 'detailSub'])->name('targetKec.detail');
    Route::get('/targetKec/edit-sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [TargetKecamatanController::class, 'editSub'])->name('targetKec.edit.sub');
    Route::post('/targetKec/update-sub', [TargetKecamatanController::class, 'updateSub'])->name('targetKec.update.sub');

    // Target Tambah Data
    Route::post('/targetKec/store-bidang', [TargetKecamatanController::class, 'storeBidang'])->name('targetKec.store.bidang');
    Route::post('/targetKec/store-kegiatan', [TargetKecamatanController::class, 'storeKegiatan'])->name('targetKec.store.kegiatan');
    Route::get('subkegiatan/create/{bidang_id}/{kegiatan_id}', [TargetKecamatanController::class, 'createSubKegiatan'])->name('targetKec.create.subkegiatan');
    Route::post('/targetKec/store-subkegiatan', [TargetKecamatanController::class, 'storeSubKegiatan'])->name('targetKec.store.subkegiatan');

    // Target Update
    Route::put('/targetKec/update-kegiatan/{id}', [TargetKecamatanController::class, 'updateKegiatan'])->name('targetKec.update.kegiatan');
    Route::put('/targetKec/update-bidang/{id}', [TargetKecamatanController::class, 'updateBidang'])->name('targetKec.update.bidang');

    // Target Delete
    Route::delete('/targetKec/delete-bidang/{id}', [TargetKecamatanController::class, 'deleteBidang'])->name('targetKec.delete.bidang');
    Route::delete('/targetKec/delete-kegiatan/{id}', [TargetKecamatanController::class, 'deleteKegiatan'])->name('targetKec.delete.kegiatan');
    Route::delete('/targetKec/delete-subKegiatan/{id}', [TargetKecamatanController::class, 'deleteSubKegiatan'])->name('targetKec.delete.subKegiatan');

    // Realisasi
    Route::get('/realisasiKec/create/sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [RealisasiController::class, 'createSub'])->name('realisasiKec.create.sub');
    Route::post('/realisasiKec/store-sub', [RealisasiController::class, 'storeSub'])->name('realisasiKec.store.sub');
    Route::get('/realisasiKec/detail/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [RealisasiController::class, 'detail'])->name('realisasiKec.detail');
    Route::delete('/realisasiKec/delete-subKegiatan/{id}', [RealisasiController::class, 'deleteSubKegiatan'])->name('realisasiKec.delete.subKegiatan');

    // Capaian
    Route::get('/capaianKec/detail/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [CapaianController::class, 'detail'])->name('capaianKec.detail');

    // Profile
    Route::get('/profileKec', [ProfileController::class, 'index'])->name('profileKec.index');
    Route::get('/profileKec/edit/{id}', [ProfileController::class, 'edit'])->name('profileKec.edit');
    Route::put('/profileKec/update/{id}', [ProfileController::class, 'update'])->name('profileKec.update');
});
