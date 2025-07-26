<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\BahanBaku;
use App\Models\Notifikasi;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            static $alreadyRun = false;
            if ($alreadyRun) return;
            $alreadyRun = true;

            $notifications = [];
            $user = Auth::user();

            if ($user) {
                $userId = $user->id;

                // 1. ambil semua bahan baku
                $bahanBakus = BahanBaku::all();

                foreach ($bahanBakus as $bahan) {
                    $nama = $bahan->nama_bahan_baku;
                    $ukuran = $bahan->ukuran;
                    $jumlah = $bahan->jumlah_stok;
                    $minimum = $bahan->stok_minimum;

                    $message = "Jumlah stok {$nama} (ukuran {$ukuran}) hampir habis. Sisa stok {$jumlah}.";

                    // cek apakah stok sudah normal
                    if ($jumlah > $minimum) {
                        // stok sudah kembali normal → hapus notifikasi terkait
                        Notifikasi::where('id_user', $userId)
                            ->where('message', 'like', "%{$nama}%")
                            ->where('is_deleted', false)
                            ->update(['is_deleted' => true]);
                    } else {
                        // stok masih di bawah batas → buat notifikasi jika belum ada
                        $existing = Notifikasi::where('id_user', $userId)
                            ->where('message', $message) // pastikan persis
                            ->where('is_deleted', false)
                            ->first();

                        if (!$existing) {
                            Notifikasi::create([
                                'id_user' => $userId,
                                'message' => $message,
                                'is_toasted' => false,
                                'is_deleted' => false,
                            ]);
                        }
                    }
                }

                // 2. ambil notifikasi aktif yang belum di-toast
                $toBeToasted = Notifikasi::where('id_user', $userId)
                    ->where('is_toasted', false)
                    ->where('is_deleted', false)
                    ->get();

                // 3. siapkan untuk ditampilkan
                $notifications = $toBeToasted->map(function ($notif) {
                    return [
                        'id' => $notif->id,
                        'message' => $notif->message,
                    ];
                });
            }

            $view->with('notifications', $notifications)
                ->with('user', $user);
        });
    }
}