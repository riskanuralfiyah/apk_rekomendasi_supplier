<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notifikasi;

use Illuminate\Http\Request;

class NotifikasiController extends Controller
{

    public function index(Request $request)
{
    $userId = Auth::id();
    $notifikasis = Notifikasi::where('id_user', $userId)
                    ->orderBy('created_at', 'desc')
                    ->get();

    $user = $request->user();

    if ($user->role == 'pemilikmebel') {
        $view = 'pages.PemilikMebel.Notifikasi.index';
    } elseif ($user->role == 'karyawan') {
        $view = 'pages.Karyawan.Notifikasi.index';
    } else {
        abort(403);
    }

    return view($view, [
        'user' => $user,
        'notifikasis' => $notifikasis,
    ]);
}


    public function markToasted()
    {
        $userId = Auth::id();

        Notifikasi::where('id_user', $userId)
            ->where('is_toasted', 0) // pakai angka 0 untuk memastikan cocok di MySQL
            ->update(['is_toasted' => true]);

        return response()->json(['status' => 'success']);
    }
}
