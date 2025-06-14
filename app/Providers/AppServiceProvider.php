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
            $notifications = [];
            $user = Auth::user();

            if ($user) {
                $userId = $user->id;

                // 1. tandai notifikasi lama sebagai selesai jika stok sudah normal
                $stokNormal = BahanBaku::whereColumn('jumlah_stok', '>', 'stok_minimum')->get();

                foreach ($stokNormal as $bahan) {
                    Notifikasi::where('id_user', $userId)
                        ->where('message', 'like', '%'.$bahan->nama_bahan_baku.'%')
                        ->where('is_deleted', false)
                        ->update(['is_deleted' => true]);
                }

                // 2. buat notifikasi baru jika stok habis dan belum ada notifikasi aktif
                $stokHabis = BahanBaku::whereColumn('jumlah_stok', '<=', 'stok_minimum')->get();

                foreach ($stokHabis as $bahan) {
                    $existing = Notifikasi::where('id_user', $userId)
                        ->where('message', 'like', '%'.$bahan->nama_bahan_baku.'%')
                        ->where('is_deleted', false)
                        ->first(); // tanpa batasan waktu

                    if (!$existing) {
                        Notifikasi::create([
                            'id_user' => $userId,
                            'message' => "Jumlah stok {$bahan->nama_bahan_baku} hampir habis. Sisa stok {$bahan->jumlah_stok}.",
                            'is_toasted' => false,
                            'is_deleted' => false,
                        ]);
                    }
                }

                // 3. ambil notifikasi aktif yang belum di-toast
                $toBeToasted = Notifikasi::where('id_user', $userId)
                    ->where('is_toasted', false)
                    ->where('is_deleted', false)
                    ->get();

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
