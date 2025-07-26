<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        if ($user->role == 'pemilikmebel') {
            $view = 'pages.PemilikMebel.KelolaProfile.edit';
        } elseif ($user->role == 'karyawan') {
            $view = 'pages.Karyawan.KelolaProfile.edit';
        } else {
            abort(403);
        }

        return view($view, ['user' => $user]);
    }


    /**
     * Update the user's profile information.
     */
     /** update first_name & last_name */

 
     /** update foto profil */
     public function updatePhoto(Request $request)
     {
         $user = $request->user();
 
         $validated = $request->validate(
            [
                'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'foto.required' => 'Foto harus diunggah.',
                'foto.image' => 'File yang diunggah harus berupa gambar.',
                'foto.mimes' => 'Format tidak didukung. Gunakan format jpeg, png, jpg, atau gif.',
                'foto.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
            ]
        );
 
         $path = $request->file('foto')->store('profile_pictures', 'public');
         $user->foto = $path;
         $user->save();
 
         return Redirect::back()->with('success', 'Foto profile berhasil diperbarui.');
     }
 
     /** update password */
     public function updatePassword(Request $request)
     {
         $user = $request->user();
 
         $request->validate(
            [
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ],
            [
                'current_password.required' => 'Password saat ini wajib diisi.',
                'new_password.required' => 'Password baru wajib diisi.',
                'new_password.min' => 'Password baru minimal harus 8 karakter.',
                'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]
        );
        
 
         if (! Hash::check($request->current_password, $user->password)) {
             return Redirect::back()
                 ->withErrors(['current_password' => 'Password lama tidak cocok.']);
         }
 
         $user->password = Hash::make($request->new_password);
         $user->save();
 
         return Redirect::back()->with('success', 'Password berhasil diperbarui.');
     }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
