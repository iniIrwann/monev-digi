<?php

use App\Http\Controllers\CapaianController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\RealisasiController;
use Illuminate\Support\Facades\Route;



Route::resource('target', TargetController::class);
Route::resource('realisasi', RealisasiController::class);
Route::resource('capaian', CapaianController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [\App\Http\Controllers\LoginController::class, 'showLoginForm'])->name('login.view');
Route::post('login', [\App\Http\Controllers\LoginController::class, 'authenticate'])->name('login');

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.index');
//Detail
Route::get('/target/detail-sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [TargetController::class, 'detailSub'])->name('target.detail');
// Edit
Route::get('/target/edit-sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [TargetController::class, 'editSub'])->name('target.edit.sub');
Route::post('/target/update-sub', [TargetController::class, 'updateSub'])->name('target.update.sub');



// Tambah Data
Route::post('/target/store-bidang', [TargetController::class, 'storeBidang'])->name('target.store.bidang');
Route::post('/target/store-kegiatan', [TargetController::class, 'storeKegiatan'])->name('target.store.kegiatan');
Route::post('/target/store-subkegiatan', [TargetController::class, 'storeSubKegiatan'])->name('target.store.subkegiatan');
Route::get('/subkegiatan/create/{bidang_id}/{kegiatan_id}', [TargetController::class, 'createSubKegiatan'])->name('target.create.subkegiatan');

// Edit
Route::put('/target/update-kegiatan/{id}', [TargetController::class, 'updateKegiatan'])->name('target.update.kegiatan');
Route::put('/target/update-bidang/{id}', [TargetController::class, 'updateBidang'])->name('target.update.bidang');


// Delete
Route::delete('/target/delete-bidang/{id}', [TargetController::class, 'deleteBidang'])->name('target.delete.bidang');
Route::delete('/target/delete-kegiatan/{id}', [TargetController::class, 'deleteKegiatan'])->name('target.delete.kegiatan');
Route::delete('/target/delete-subKegiatan/{id}', [TargetController::class, 'deleteSubKegiatan'])->name('target.delete.subKegiatan');


// Realisasi
// Tambah Data
Route::get('/realisasi/create/sub/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [RealisasiController::class, 'createSub'])->name('realisasi.create.sub');
Route::post('/realisasi/store-sub', [RealisasiController::class, 'storeSub'])->name('realisasi.store.sub');

Route::get('/realisasi/detail/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [RealisasiController::class, 'detail'])->name('realisasi.detail');

//Delete
Route::delete('/realisasi/delete-subKegiatan/{id}', [RealisasiController::class, 'deleteSubKegiatan'])->name('realisasi.delete.subKegiatan');

// Detail
Route::get('/capaian/detail/{bidang_id}/{kegiatan_id}/{subkegiatan_id}', [CapaianController::class, 'detail'])->name('capaian.detail');

//Editt Profile
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile/edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
