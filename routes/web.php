<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SuratPemesananController;
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

Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
Route::post('/notifikasi/mark-toasted', [NotifikasiController::class, 'markToasted'])->name('notifikasi.marktoasted');
Route::put('/notifikasi/{id}/softdelete', [NotifikasiController::class, 'softDelete'])->name('notifikasi.softdelete');


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
            Route::delete('/{kriteriaId}/{subkriteriaId}', [DataSubKriteriaController::class, 'destroy'])->name('delete.datasubkriteria.pemilikmebel');

    });

    // Penilaian Supplier
        Route::prefix('penilaian')->group(function () {
            Route::get('/{supplierId}', [PenilaianSupplierController::class, 'index'])->name('penilaiansupplier.pemilikmebel');
            Route::get('/{supplierId}/create', [PenilaianSupplierController::class, 'create'])->name('create.penilaiansupplier.pemilikmebel');
            Route::post('/{supplierId}', [PenilaianSupplierController::class, 'store'])->name('store.penilaiansupplier.pemilikmebel');
            Route::get('/{supplierId}/edit', [PenilaianSupplierController::class, 'edit'])->name('edit.penilaiansupplier.pemilikmebel');
            Route::get('/{supplierId}/{id}', [PenilaianSupplierController::class, 'show'])->name('show.penilaiansupplier.pemilikmebel');
            Route::put('/{supplierId}', [PenilaianSupplierController::class, 'update'])->name('update.penilaiansupplier.pemilikmebel');
            Route::delete('/{supplierId}', [PenilaianSupplierController::class, 'destroy'])->name('destroy.penilaiansupplier.pemilikmebel');
        });
    
    // Data Perhitungan
    Route::get('/data-perhitungan', [DataPerhitunganController::class, 'hitung'])->name('dataperhitungan.pemilikmebel');
    
    // Hasil Rekomendasi
    Route::get('/hasil-rekomendasi', [HasilRekomendasiController::class, 'index'])->name('hasilrekomendasi.pemilikmebel');
    Route::get('/hasil-rekomendasi/pdf', [HasilRekomendasiController::class, 'exportToPdf'])->name('pdf.hasilrekomendasi.pemilikmebel');
    
    // Data Bahan Baku
    Route::get('/data-bahan-baku', [DataBahanBakuPemilikMebelController::class, 'index'])->name('databahanbaku.pemilikmebel');
    
    // Laporan Bahan Baku
    Route::prefix('laporan-bahan-baku')->group(function () {
        Route::get('/', [LaporanBahanBakuController::class, 'index'])->name('laporanbahanbaku.pemilikmebel');
        Route::get('/show', [LaporanBahanBakuController::class, 'show'])->name('show.laporanbahanbaku.pemilikmebel');
        Route::get('/pdf', [LaporanBahanBakuController::class, 'exportToPdf'])->name('pdf.laporanbahanbaku.pemilikmebel');
        Route::get('/excel', [LaporanBahanBakuController::class, 'exportToExcel'])->name('excel.laporanbahanbaku.pemilikmebel');

    });
    // Kelola Pengguna
    Route::prefix('kelola-pengguna')->group(function () {
        Route::get('/', [KelolaPenggunaController::class, 'index'])->name('kelolapengguna.pemilikmebel');
        Route::get('/create', [KelolaPenggunaController::class, 'create'])->name('create.kelolapengguna.pemilikmebel');
        Route::post('/', [KelolaPenggunaController::class, 'store'])->name('store.kelolapengguna.pemilikmebel');
        Route::get('/{id}', [KelolaPenggunaController::class, 'show'])->name('detail.kelolapengguna.pemilikmebel');
        Route::get('/{id}/edit', [KelolaPenggunaController::class, 'edit'])->name('edit.kelolapengguna.pemilikmebel');
        Route::put('/{id}', [KelolaPenggunaController::class, 'update'])->name('update.kelolapengguna.pemilikmebel');
        Route::delete('/{id}', [KelolaPenggunaController::class, 'destroy'])->name('delete.kelolapengguna.pemilikmebel');
    });

    Route::get('/surat-pemesanan', [SuratPemesananController::class, 'index'])->name('suratpemesanan.pemilikmebel');
    Route::post('/surat-pemesanan/pdf', [SuratPemesananController::class, 'buatSurat'])->name('pdf.suratpemesanan.pemilikmebel');
});

// ROUTE UNTUK KARYAWAN (TANPA MIDDLEWARE ROLE)
Route::prefix('karyawan')->middleware(['auth', 'role:karyawan'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardKaryawanController::class, 'index'])->name('dashboard.karyawan');
    
    // Data Bahan Baku
    Route::prefix('data-bahan-baku')->group(function () {
        Route::get('/', [DataBahanBakuKaryawanController::class, 'index'])->name('databahanbaku.karyawan');
        Route::get('/create', [DataBahanBakuKaryawanController::class, 'create'])->name('create.databahanbaku.karyawan');
        Route::post('/', [DataBahanBakuKaryawanController::class, 'store'])->name('store.databahanbaku.karyawan');
        Route::get('/{id}', [DataBahanBakuKaryawanController::class, 'show'])->name('detail.databahanbaku.karyawan');
        Route::get('/{id}/edit', [DataBahanBakuKaryawanController::class, 'edit'])->name('edit.databahanbaku.karyawan');
        Route::put('/{id}', [DataBahanBakuKaryawanController::class, 'update'])->name('update.databahanbaku.karyawan');
        Route::delete('/{id}', [DataBahanBakuKaryawanController::class, 'destroy'])->name('delete.databahanbaku.karyawan');
    });
    
    // Stok Masuk
    Route::prefix('stok-masuk')->group(function () {
        Route::get('/', [StokMasukController::class, 'index'])->name('stokmasuk.karyawan');
        Route::get('/create', [StokMasukController::class, 'create'])->name('create.stokmasuk.karyawan');
        Route::post('/', [StokMasukController::class, 'store'])->name('store.stokmasuk.karyawan');
        Route::get('/{id}', [StokMasukController::class, 'show'])->name('detail.stokmasuk.karyawan');
        Route::get('/{id}/edit', [StokMasukController::class, 'edit'])->name('edit.stokmasuk.karyawan');
        Route::put('/{id}', [StokMasukController::class, 'update'])->name('update.stokmasuk.karyawan');
        Route::delete('/{id}', [StokMasukController::class, 'destroy'])->name('delete.stokmasuk.karyawan');
    });
    
    // Stok Keluar
    Route::prefix('stok-keluar')->group(function () {
        Route::get('/', [StokKeluarController::class, 'index'])->name('stokkeluar.karyawan');
        Route::get('/create', [StokKeluarController::class, 'create'])->name('create.stokkeluar.karyawan');
        Route::post('/', [StokKeluarController::class, 'store'])->name('store.stokkeluar.karyawan');
        Route::get('/{id}', [StokKeluarController::class, 'show'])->name('detail.stokkeluar.karyawan');
        Route::get('/{id}/edit', [StokKeluarController::class, 'edit'])->name('edit.stokkeluar.karyawan');
        Route::put('/{id}', [StokKeluarController::class, 'update'])->name('update.stokkeluar.karyawan');
        Route::delete('/{id}', [StokKeluarController::class, 'destroy'])->name('delete.stokkeluar.karyawan');
    });

    Route::get('/surat-pemesanan', [SuratPemesananController::class, 'index'])->name('suratpemesanan.karyawan');
    Route::post('/surat-pemesanan/pdf', [SuratPemesananController::class, 'buatSurat'])->name('pdf.suratpemesanan.karyawan');
});

require __DIR__.'/auth.php';