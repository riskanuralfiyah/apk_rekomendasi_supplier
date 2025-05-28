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
        
                // cek stok rendah dan buat notifikasi baru jika belum ada
                $stokHabis = BahanBaku::whereColumn('jumlah_stok', '<=', 'stok_minimum')->get();
        
                foreach ($stokHabis as $bahan) {
                    $existing = Notifikasi::where('id_user', $userId)
                        ->where('message', 'like', '%'.$bahan->nama_bahan_baku.'%')
                        ->first();
        
                    if (!$existing) {
                        Notifikasi::create([
                            'id_user' => $userId,
                            'message' => "Jumlah stok {$bahan->nama_bahan_baku} hampir habis. Sisa stok {$bahan->jumlah_stok}.",
                            'is_read' => false,
                            'is_toasted' => false,
                        ]);
                    }
                }
        
                // ambil notifikasi yang belum di-toast, tapi jangan update status di sini
                $toBeToasted = Notifikasi::where('id_user', $userId)
                    ->where('is_toasted', false)
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