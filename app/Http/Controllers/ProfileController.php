<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

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
 
         $validated = $request->validate([
             'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
         ]);
 
         $path = $request->file('foto')->store('profile_pictures', 'public');
         $user->foto = $path;
         $user->save();
 
         return Redirect::back()->with('success', 'Foto profil berhasil diubah.');
     }
 
     /** update password */
     public function updatePassword(Request $request)
     {
         $user = $request->user();
 
         $request->validate([
             'current_password'          => 'required',
             'new_password'              => 'required|min:8|confirmed',
         ]);
 
         if (! Hash::check($request->current_password, $user->password)) {
             return Redirect::back()
                 ->withErrors(['current_password' => 'Password lama tidak cocok.']);
         }
 
         $user->password = Hash::make($request->new_password);
         $user->save();
 
         return Redirect::back()->with('success', 'Password berhasil diubah.');
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
