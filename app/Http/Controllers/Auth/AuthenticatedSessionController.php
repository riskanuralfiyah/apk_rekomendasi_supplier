<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Mail\KodeVerifikasiMail;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // jika user belum verifikasi, kirim kode OTP ke email
        if (!$user->is_verified) {
            $otp = random_int(100000, 999999);

            // simpan otp dan waktu kadaluarsa ke session
            session([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
                'otp_user_id' => $user->id
            ]);

            // kirim email
            Mail::to($user->email)->send(new KodeVerifikasiMail($otp));

            return redirect()->route('auth.otp.form'); // arahkan ke form OTP
        }

        // jika sudah verifikasi, arahkan ke dashboard sesuai role
        if ($user->role === 'pemilikmebel') {
            return redirect()->intended(route('dashboard.pemilikmebel', absolute: false));
        } elseif ($user->role === 'karyawan') {
            return redirect()->intended(route('dashboard.karyawan', absolute: false));
        } else {
            return redirect()->intended(route('login', absolute: false));
        }
    }

    public function showOtpForm(): View
{
    return view('auth.otp');
}

public function verifyOtp(Request $request): RedirectResponse
{
    // validasi input OTP
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    // ambil OTP dari session
    $sessionOtp = session('otp');
    $expiresAt = session('otp_expires_at');
    $userId = session('otp_user_id');

    // cek apakah OTP sudah expired
    if (!$sessionOtp || !$expiresAt || now()->greaterThan($expiresAt)) {
        return redirect()->route('auth.otp.form')->withErrors(['otp' => 'Kode OTP sudah kadaluarsa.']);
    }

    // cek apakah OTP yang dimasukkan sesuai dengan yang ada di session
    if ($request->otp != $sessionOtp) {
        return redirect()->route('auth.otp.form')->withErrors(['otp' => 'Kode OTP tidak valid.']);
    }

    // update status verifikasi user
    $user = \App\Models\User::find($userId);
    if ($user) {
        $user->is_verified = true;
        $user->save();
    }

    // hapus OTP dari session
    session()->forget(['otp', 'otp_expires_at', 'otp_user_id']);

    // arahkan kembali ke dashboard sesuai role
    if ($user->role === 'pemilikmebel') {
        return redirect()->route('dashboard.pemilikmebel');
    } elseif ($user->role === 'karyawan') {
        return redirect()->route('dashboard.karyawan');
    }

    return redirect('/');
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
