<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PemilikMebel\DashboardPemilikMebelController;
use App\Http\Controllers\PemilikMebel\DataSupplierController;
use App\Http\Controllers\PemilikMebel\DataKriteriaController;
use App\Http\Controllers\PemilikMebel\DataSubKriteriaController;
use App\Http\Controllers\PemilikMebel\PenilaianSupplierController;
use App\Http\Controllers\PemilikMebel\DataPerhitunganController;
use App\Http\Controllers\PemilikMebel\HasilRekomendasiController;
use App\Http\Controllers\PemilikMebel\DataBahanBakuPemilikMebelController;
use App\Http\Controllers\PemilikMebel\LaporanBahanBakuController;
use App\Http\Controllers\PemilikMebel\KelolaPenggunaController;

use App\Http\Controllers\Karyawan\DashboardKaryawanController;
use App\Http\Controllers\Karyawan\DataBahanBakuKaryawanController;
use App\Http\Controllers\Karyawan\StokMasukController;
use App\Http\Controllers\Karyawan\StokKeluarController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    // tampilkan form edit profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // update nama depan & belakang
    Route::patch('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // update foto profil
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');

    // update password
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});


Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
// tampilan form OTP
Route::get('/otp-verifikasi', [AuthenticatedSessionController::class, 'showOtpForm'])->name('auth.otp.form');
// proses submit OTP
Route::post('/otp-verifikasi', [AuthenticatedSessionController::class, 'verifyOtp'])->name('auth.otp.verify');


// ROUTE UNTUK PEMILIK MEBEL (TANPA MIDDLEWARE ROLE)
Route::prefix('pemilikmebel')->middleware(['auth', 'role:pemilikmebel'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardPemilikMebelController::class, 'index'])->name('dashboard.pemilikmebel');
    

    // Data Supplier
    Route::prefix('data-supplier')->group(function () {
        Route::get('/', [DataSupplierController::class, 'index'])->name('datasupplier.pemilikmebel');
        Route::get('/create', [DataSupplierController::class, 'create'])->name('create.datasupplier.pemilikmebel');
        Route::post('/', [DataSupplierController::class, 'store'])->name('store.datasupplier.pemilikmebel');
        Route::get('/{id}', [DataSupplierController::class, 'show'])->name('detail.datasupplier.pemilikmebel');
        Route::get('/{id}/edit', [DataSupplierController::class, 'edit'])->name('edit.datasupplier.pemilikmebel');
        Route::put('/{id}', [DataSupplierController::class, 'update'])->name('update.datasupplier.pemilikmebel');
        Route::delete('/{id}', [DataSupplierController::class, 'destroy'])->name('delete.datasupplier.pemilikmebel');
    });

    // Data Kriteria
    Route::prefix('data-kriteria')->group(function () {
        Route::get('/', [DataKriteriaController::class, 'index'])->name('datakriteria.pemilikmebel');
        Route::get('/create', [DataKriteriaController::class, 'create'])->name('create.datakriteria.pemilikmebel');
        Route::post('/', [DataKriteriaController::class, 'store'])->name('store.datakriteria.pemilikmebel');
        Route::get('/{id}', [DataKriteriaController::class, 'show'])->name('detail.datakriteria.pemilikmebel');
        Route::get('/{id}/edit', [DataKriteriaController::class, 'edit'])->name('edit.datakriteria.pemilikmebel');
        Route::put('/{id}', [DataKriteriaController::class, 'update'])->name('update.datakriteria.pemilikmebel');
        Route::delete('/{id}', [DataKriteriaController::class, 'destroy'])->name('delete.datakriteria.pemilikmebel');
    });
    
    // Data Sub Kriteria
    Route::prefix('data-subkriteria')->group(function () {
            Route::get('/{kriteriaId}', [DataSubKriteriaController::class, 'index'])->name('datasubkriteria.pemilikmebel');
            Route::get('/{kriteriaId}/create', [DataSubKriteriaController::class, 'create'])->name('create.datasubkriteria.pemilikmebel');
            Route::post('/{kriteriaId}', [DataSubKriteriaController::class, 'store'])->name('store.datasubkriteria.pemilikmebel');
            Route::get('/{kriteriaId}/{id}', [DataSubKriteriaController::class, 'show'])->name('detail.datasubkriteria.pemilikmebel');
            Route::get('/{kriteriaId}/{id}/edit', [DataSubKriteriaController::class, 'edit'])->name('edit.datasubkriteria.pemilikmebel');
            Route::put('/{kriteriaId}/{id}', [DataSubKriteriaController::class, 'update'])->name('update.datasubkriteria.pemilikmebel');
            Route::delete('/{kriteriaId/{subkriteriaId}', [DataSubKriteriaController::class, 'destroy'])->name('delete.datasubkriteria.pemilikmebel');
    });

    // Penilaian Supplier
        Route::prefix('penilaian')->group(function () {
            Route::get('/{supplierId}', [PenilaianSupplierController::class, 'index'])->name('penilaiansupplier.pemilikmebel');
            Route::get('/{supplierId}/create', [PenilaianSupplierController::class, 'create'])->name('create.penilaiansupplier.pemilikmebel');
            Route::post('/{supplierId}', [PenilaianSupplierController::class, 'store'])->name('store.penilaiansupplier.pemilikmebel');
            Route::get('/{supplierId}/{id}', [PenilaianSupplierController::class, 'show'])->name('show.penilaiansupplier.pemilikmebel');
            Route::get('/{supplierId}/{id}/edit', [PenilaianSupplierController::class, 'edit'])->name('edit.penilaiansupplier.pemilikmebel');
            Route::put('/{supplierId}/{id}', [PenilaianSupplierController::class, 'update'])->name('update.penilaiansupplier.pemilikmebel');
            Route::delete('/{supplierId}/{penilaianId}', [PenilaianSupplierController::class, 'destroy'])->name('destroy.penilaiansupplier.pemilikmebel');
        });
    
    // Data Perhitungan
    Route::get('/data-perhitungan', [DataPerhitunganController::class, 'index'])->name('dataperhitungan.pemilikmebel');
    
    // Hasil Rekomendasi
    Route::get('/hasil-rekomendasi', [HasilRekomendasiController::class, 'index'])->name('hasilrekomendasi.pemilikmebel');
    
    // Data Bahan Baku
    Route::get('/data-bahan-baku', [DataBahanBakuPemilikMebelController::class, 'index'])->name('databahanbaku.pemilikmebel');
    
    // Laporan Bahan Baku
    Route::get('/laporan-stok', [LaporanBahanBakuController::class, 'index'])->name('laporanbahanbaku.pemilikmebel');
    
    // Kelola Pengguna
    Route::prefix('data-pengguna')->group(function () {
        Route::get('/', [KelolaPenggunaController::class, 'index'])->name('kelolapengguna.pemilikmebel');
        Route::get('/create', [KelolaPenggunaController::class, 'create'])->name('create.kelolapengguna.pemilikmebel');
        Route::get('/edit', [KelolaPenggunaController::class, 'edit'])->name('edit.kelolapengguna.pemilikmebel');
        Route::get('/delete', [KelolaPenggunaController::class, 'delete'])->name('delete.kelolapengguna.pemilikmebel');
    });
});

// ROUTE UNTUK KARYAWAN (TANPA MIDDLEWARE ROLE)
Route::prefix('karyawan')->middleware(['auth', 'role:karyawan'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardKaryawanController::class, 'index'])->name('dashboard.karyawan');
    
    // Data Bahan Baku
    Route::prefix('data-bahan-baku')->group(function () {
        Route::get('/', [DataBahanBakuKaryawanController::class, 'index'])->name('databahanbaku.karyawan');
        Route::get('/create', [DataBahanBakuKaryawanController::class, 'create'])->name('create.databahanbaku.karyawan');
        Route::get('/edit', [DataBahanBakuKaryawanController::class, 'edit'])->name('edit.databahanbaku.karyawan');
        Route::get('/delete', [DataBahanBakuKaryawanController::class, 'delete'])->name('delete.databahanbaku.karyawan');
    });
    
    // Stok Masuk
    Route::prefix('stok-masuk')->group(function () {
        Route::get('/', [StokMasukController::class, 'index'])->name('stokmasuk.karyawan');
        Route::get('/create', [StokMasukController::class, 'create'])->name('create.stokmasuk.karyawan');
        Route::get('/edit', [StokMasukController::class, 'edit'])->name('edit.stokmasuk.karyawan');
        Route::get('/delete', [StokMasukController::class, 'delete'])->name('delete.stokmasuk.karyawan');
    });
    
    // Stok Keluar
    Route::prefix('stok-keluar')->group(function () {
        Route::get('/', [StokKeluarController::class, 'index'])->name('stokkeluar.karyawan');
        Route::get('/create', [StokKeluarController::class, 'create'])->name('create.stokkeluar.karyawan');
        Route::get('/edit', [StokKeluarController::class, 'edit'])->name('edit.stokkeluar.karyawan');
        Route::get('/delete', [StokKeluarController::class, 'delete'])->name('delete.stokkeluar.karyawan');
    });
});

require __DIR__.'/auth.php';