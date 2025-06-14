<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataBahanBakuKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search', '');
        $statusFilter = $request->input('status', '');

        $query = BahanBaku::query();

        if (!empty($searchTerm)) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_bahan_baku', 'like', '%' . $searchTerm . '%')
                  ->orWhere('ukuran', 'like', '%' . $searchTerm . '%');
            });
        }

                // filter status: aman atau perlu restock
                if ($statusFilter === 'aman') {
                    $query->whereColumn('jumlah_stok', '>', 'stok_minimum');
                } elseif ($statusFilter === 'perlu_restock') {
                    $query->whereColumn('jumlah_stok', '<=', 'stok_minimum');
                }

        $bahanbakus = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'search' => $searchTerm,
                'status' => $statusFilter,
            ]);

        return view('pages.Karyawan.DataBahanBaku.index', compact('bahanbakus'));
    }

    public function create()
    {
        return view('pages.Karyawan.DataBahanBaku.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku' => 'required|string|max:100',
            'ukuran' => 'required|string|max:20',
            'stok_minimum' => 'required|integer|min:0',
            'jumlah_stok' => 'required|integer|min:0',
        ], [
            'nama_bahan_baku.required' => 'Nama bahan baku harus diisi.',
            'nama_bahan_baku.string' => 'Nama bahan baku harus berupa teks.',
            'nama_bahan_baku.max' => 'Nama bahan baku maksimal 100 karakter.',
            'ukuran.required' => 'Ukuran harus diisi.',
            'ukuran.string' => 'Ukuran harus berupa teks.',
            'ukuran.max' => 'Ukuran maksimal 20 karakter.',
            'stok_minimum.required' => 'Stok minimum harus diisi.',
            'stok_minimum.integer' => 'Stok minimum harus berupa angka.',
            'stok_minimum.min' => 'Stok minimum tidak boleh negatif.',
            'jumlah_stok.required' => 'Jumlah stok harus diisi.',
            'jumlah_stok.integer' => 'Jumlah stok harus berupa angka.',
            'jumlah_stok.min' => 'Jumlah stok tidak boleh negatif.',
        ]);
        

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400); // status 400 bad request
        }

        // cek duplikat
        $existing = BahanBaku::where('nama_bahan_baku', $request->nama_bahan_baku)
            ->where('ukuran', $request->ukuran)
            ->first();
    
        if ($existing) {
            return response()->json([
                'errors' => [
                    'duplicate' => ['Data bahan baku dengan nama yang sama sudah ada.']
                ]
            ], 400);
        }
    
        try {
            BahanBaku::create([
                'nama_bahan_baku' => $request->nama_bahan_baku,
                'ukuran' => $request->ukuran,
                'stok_minimum' => $request->stok_minimum,
                'jumlah_stok' => $request->jumlah_stok,
            ]);
    
            return response()->json([
                'message' => 'Data bahan baku berhasil ditambahkan'
            ], 200); // OK
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data bahan baku. Silakan coba lagi.'
            ], 400); // Internal Server Error
        }
    }

    public function show($id)
    {
        $bahanbaku = BahanBaku::findOrFail($id);
        return view('pages.Karyawan.DataBahanBaku.detail', compact('bahanbaku'));
    }

    public function edit($id)
    {
        $bahanbaku = BahanBaku::findOrFail($id);
        return view('pages.Karyawan.DataBahanBaku.edit', compact('bahanbaku'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_bahan_baku' => 'required|string|max:100',
            'ukuran' => 'required|string|max:20',
            'stok_minimum' => 'required|integer|min:0',
            'jumlah_stok' => 'required|integer|min:0',
        ], [
            'nama_bahan_baku.required' => 'Nama bahan baku harus diisi.',
            'nama_bahan_baku.string' => 'Nama bahan baku harus berupa teks.',
            'nama_bahan_baku.max' => 'Nama bahan baku maksimal 100 karakter.',
            'ukuran.required' => 'Ukuran harus diisi.',
            'ukuran.string' => 'Ukuran harus berupa teks.',
            'ukuran.max' => 'Ukuran maksimal 20 karakter.',
            'stok_minimum.required' => 'Stok minimum harus diisi.',
            'stok_minimum.integer' => 'Stok minimum harus berupa angka.',
            'stok_minimum.min' => 'Stok minimum tidak boleh negatif.',
            'jumlah_stok.required' => 'Jumlah stok harus diisi.',
            'jumlah_stok.integer' => 'Jumlah stok harus berupa angka.',
            'jumlah_stok.min' => 'Jumlah stok tidak boleh negatif.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400); // status 400 bad request
        }

        // cek duplikat
        $existing = BahanBaku::where('nama_bahan_baku', $request->nama_bahan_baku)
            ->where('id', '!=', $id)
            ->where('ukuran', $request->ukuran)
            ->first();
    
        if ($existing) {
            return response()->json([
                'errors' => [
                    'duplicate' => ['Data bahan baku dengan nama yang sama sudah ada.']
                ]
            ], 400);
        }
    
        try {
            $bahanbaku = BahanBaku::findOrFail($id);
            $bahanbaku->update([
                'nama_bahan_baku' => $request->nama_bahan_baku,
                'ukuran' => $request->ukuran,
                'stok_minimum' => $request->stok_minimum,
                'jumlah_stok' => $request->jumlah_stok,
            ]);
    
            return response()->json([
                'message' => 'Data bahan baku berhasil diperbarui'
            ], 200); // OK
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data bahan baku. Silakan coba lagi.'
            ], 400); // Internal Server Error
        }
    }

    public function destroy($id)
    {
        $bahanbaku = BahanBaku::findOrFail($id);

        // Cek jika bahan baku memiliki relasi dengan data lain
        if (
            $bahanbaku->laporans()->exists() ||
            $bahanbaku->stokKeluars()->exists() ||
            $bahanbaku->stokMasuks()->exists() // tambahkan relasi lain jika ada
        ) {
            return response()->json([
                'message' => 'Tidak dapat menghapus data bahan baku karena masih memiliki data terkait.'
            ], 400);
        }

        $bahanbaku->delete();

        return response()->json([
            'message' => 'Data bahan baku berhasil dihapus.'
        ]);
    }
}
