<?php

namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelolaPenggunaController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search', '');

        $query = User::query();

        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_pengguna', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('role', 'like', '%' . $searchTerm . '%');
            });
        }

        $penggunas = $query->orderBy('created_at', 'desc')
                         ->paginate($perPage)
                         ->appends([
                             'per_page' => $perPage,
                             'search' => $searchTerm
                         ]);

        return view('pages.PemilikMebel.KelolaPengguna.index', compact('penggunas'));
    }

    public function create()
    {
        return view('pages.PemilikMebel.KelolaPengguna.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pengguna' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:pemilikmebel,karyawan'
        ], [
            'nama_pengguna.required' => 'Nama pengguna harus diisi.',
            'nama_pengguna.string' => 'Nama pengguna harus berupa teks.',
            'nama_pengguna.max' => 'Nama pengguna maksimal 100 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role hanya boleh pemilik mebel atau karyawan.'
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400); // status 400 bad request
        }

        // cek duplikat
        $existing = User::where('nama_pengguna', $request->nama_pengguna)
            ->where('email', $request->email)
            ->where('role', $request->role)
            ->first();
    
        if ($existing) {
            return response()->json([
                'errors' => [
                    'duplicate' => ['Data pengguna dengan nama, email, dan role yang sama sudah ada.']
                ]
            ], 400);
        }
    
        try {
            User::create([
                'nama_pengguna' => $request->nama_pengguna,
                'email' => $request->email,
                'role' => $request->role,
                'password' => bcrypt('password123') // default password
            ]);
    
            return response()->json([
                'message' => 'Data pengguna berhasil ditambahkan'
            ], 200); // OK
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data pengguna. Silakan coba lagi.'
            ], 400); // Internal Server Error
        }
    }

    public function show($id)
    {
        $pengguna = User::findOrFail($id);
        return view('pages.PemilikMebel.KelolaPengguna.detail', compact('pengguna'));
    }

    public function edit($id)
    {
        $pengguna = User::findOrFail($id);
        return view('pages.PemilikMebel.KelolaPengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_pengguna' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:pemilikmebel,karyawan'
        ], [
            'nama_pengguna.required' => 'Nama pengguna harus diisi.',
            'nama_pengguna.string' => 'Nama pengguna harus berupa teks.',
            'nama_pengguna.max' => 'Nama pengguna maksimal 100 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'role.required' => 'Role harus dipilih.',
            'role.in' => 'Role hanya boleh pemilik mebel atau karyawan.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        // cek duplikat
        $existing = User::where('nama_pengguna', $request->nama_pengguna)
            ->where('email', $request->email)
            ->where('role', $request->role)
            ->where('id', '!=', $id)
            ->first();
    
        if ($existing) {
            return response()->json([
                'errors' => [
                    'duplicate' => ['Data pengguna dengan nama, email, dan role yang sama sudah ada.']
                ]
            ], 400);
        }
    
        try {
            $pengguna = User::findOrFail($id);
            $pengguna->update([
                'nama_pengguna' => $request->nama_pengguna,
                'email' => $request->email,
                'role' => $request->role
            ]);
    
            return response()->json([
                'message' => 'Data pengguna berhasil diperbarui'
            ], 200); // OK
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data pengguna. Silakan coba lagi.'
            ], 400); // Internal Server Error
        }
    }

    public function destroy($id)
    {
        $pengguna = User::findOrFail($id);

        // Hindari menghapus diri sendiri (opsional)
        if (auth()->id() == $pengguna->id) {
            return response()->json([
                'message' => 'Tidak bisa menghapus akun sendiri.'
            ], 400);
        }

        $pengguna->delete();

        return response()->json([
            'message' => 'Data pengguna berhasil dihapus.'
        ]);
    }
}
