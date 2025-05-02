<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class LoginVerificationController extends Controller
{
    public function form()
    {
        return view('auth.verifikasi-login');
    }

    public function verify(Request $request)
    {
        $request->validate(['kode' => 'required']);

        if ($request->kode == session('login_verification_code')) {
            // verifikasi berhasil
            $user = Auth::user();
            $user->update(['last_login_at' => now()]);

            // arahkan ke dashboard
            if ($user->role === 'pemilikmebel') {
                return redirect()->route('dashboard.pemilikmebel');
            } elseif ($user->role === 'karyawan') {
                return redirect()->route('dashboard.karyawan');
            }
        }

        return back()->withErrors(['kode' => 'Kode verifikasi salah.']);
    }
}

