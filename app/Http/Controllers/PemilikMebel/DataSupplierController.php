<?php

// app/Http/Controllers/PemilikMebel/DataSupplierController.php
namespace App\Http\Controllers\PemilikMebel;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataSupplierController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter dari request
        $perPage = $request->input('per_page', 10); // Default 10 item per halaman
        $searchTerm = $request->input('search', ''); // Term pencarian
        
        // Query dasar
        $query = Supplier::query();
        $query->withCount('penilaians');
        
        // Jika ada pencarian
        if (!empty($searchTerm)) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_supplier', 'like', '%' . $searchTerm . '%')
                  ->orWhere('alamat', 'like', '%' . $searchTerm . '%')
                  ->orWhere('no_telpon', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Urutkan berdasarkan yang terbaru dan paginasi
        $suppliers = $query->orderBy('created_at', 'desc')
                         ->paginate($perPage)
                         ->appends([
                             'per_page' => $perPage,
                             'search' => $searchTerm
                         ]);
        
        return view('pages.PemilikMebel.DataSupplier.index', compact('suppliers'));
    }


    public function create()
    {
        return view('pages.PemilikMebel.DataSupplier.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telpon' => 'required|string|max:20'
        ], [
            'nama_supplier.required' => 'Nama supplier harus diisi.',
            'alamat.required' => 'Alamat harus diisi.',
            'no_telpon.required' => 'Nomor telepon harus diisi.',
            'no_telpon.max' => 'Nomor telepon maksimal 20 karakter.'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400); // status 400 bad request
        }
    
        // cek duplikat
        $existing = Supplier::where('nama_supplier', $request->nama_supplier)
            ->where('alamat', $request->alamat)
            ->where('no_telpon', $request->no_telpon)
            ->first();
    
        if ($existing) {
            return response()->json([
                'errors' => [
                    'duplicate' => ['Data supplier dengan nama, alamat, dan no telpon yang sama sudah ada.']
                ]
            ], 400);
        }
    
        try {
            Supplier::create([
                'nama_supplier' => $request->nama_supplier,
                'alamat' => $request->alamat,
                'no_telpon' => $request->no_telpon
            ]);
    
            return response()->json([
                'message' => 'Data supplier berhasil ditambahkan'
            ], 200); // OK
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan data supplier. Silakan coba lagi.'
            ], 400); // Internal Server Error
        }
    }
    
    

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('pages.PemilikMebel.DataSupplier.detail', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('pages.PemilikMebel.DataSupplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'required|string',
            'no_telpon' => 'required|string|max:20'
        ], [
            'nama_supplier.required' => 'Nama supplier harus diisi.',
            'alamat.required' => 'Alamat harus diisi.',
            'no_telpon.required' => 'Nomor telepon harus diisi.',
            'no_telpon.max' => 'Nomor telepon maksimal 20 karakter.'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
    
        // cek duplikat, kecuali dirinya sendiri
        $existing = Supplier::where('nama_supplier', $request->nama_supplier)
            ->where('alamat', $request->alamat)
            ->where('no_telpon', $request->no_telpon)
            ->where('id', '!=', $id)
            ->first();
    
        if ($existing) {
            return response()->json([
                'errors' => [
                    'duplicate' => ['Data supplier dengan nama, alamat, dan no telpon yang sama sudah ada.']
                ]
            ], 400);
        }
    
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->update([
                'nama_supplier' => $request->nama_supplier,
                'alamat' => $request->alamat,
                'no_telpon' => $request->no_telpon
            ]);
    
            return response()->json([
                'message' => 'Data supplier berhasil diperbarui'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui data supplier. Silakan coba lagi.'
            ], 400);
        }
    }
    

    public function destroy($id)
    {

        // Ambil data supplier
        $supplier = Supplier::findOrFail($id);

        // Cek jika supplier memiliki relasi dengan data lain
        if (
            $supplier->penilaians()->exists() ||
            $supplier->hasilRekomendasis()->exists() ||
            $supplier->stokMasuks()->exists() // tambahkan relasi lain jika ada
        ) {
            return response()->json([
                'message' => 'Tidak dapat menghapus data supplier karena masih memiliki data terkait.'
            ], 400);
        }

        // Hapus supplier jika tidak ada relasi yang terdeteksi
        $supplier->delete();

        return response()->json([
            'message' => 'Data supplier berhasil dihapus.'
        ]);
    }
}